const express = require('express');
const { Client, GatewayIntentBits, EmbedBuilder } = require('discord.js');
const axios = require('axios');
const bodyParser = require('body-parser');

const app = express();
const port = 3001;

const client = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMessages,
        GatewayIntentBits.MessageContent,
        GatewayIntentBits.DirectMessages,
        GatewayIntentBits.GuildMembers,
    ],
    partials: ['CHANNEL'],
});

require('dotenv').config();
const DISCORD_TOKEN = process.env.DISCORD_TOKEN;
const LARAVEL_API_URL = process.env.LARAVEL_API_URL || 'http://localhost:8000';
const GUILD_ID = '1081147737040486500'; // Replace with your guild ID

app.use(bodyParser.json());

const processing = new Set();

client.login(DISCORD_TOKEN);

client.once('ready', () => {
    console.log(`Logged in as ${client.user.tag}`);
});

function formatPriceMessage(gameId, region, prices, header, footer) {
    let message = '';
    if (header) {
        message += `${header}\n\n`;
    }
    prices.forEach(({ realm, price }) => {
        message += `**${realm}**: ${price} Toman\n`;
    });
    if (footer) {
        message += `\n${footer}`;
    }
    return message;
}

async function deleteExistingMessages(channel, gameId, region, header, footer) {
    try {
        let lastId = null;
        let totalDeleted = 0;
        while (true) {
            const messages = await channel.messages.fetch({ limit: 100, before: lastId });
            if (messages.size === 0) break;

            const priceMessages = messages.filter(msg => 
                msg.author.id === client.user.id && 
                (msg.content.includes('Toman') || 
                 (header && msg.content.includes(header)) || 
                 (footer && msg.content.includes(footer)))
            );

            if (priceMessages.size > 0) {
                await Promise.all(priceMessages.map(async (msg) => {
                    try {
                        await msg.delete();
                        totalDeleted++;
                        console.log(`Deleted message ${msg.id} for game ${gameId} in ${region}`);
                    } catch (err) {
                        console.error(`Failed to delete message ${msg.id} for game ${gameId} in ${region}: ${err.message}`);
                    }
                }));
                console.log(`Deleted ${priceMessages.size} messages in this batch for game ${gameId} in ${region}`);
            }

            lastId = messages.last()?.id;
        }
        console.log(`Total deleted ${totalDeleted} existing messages for game ${gameId} in ${region}`);
        return totalDeleted;
    } catch (error) {
        console.error(`Error deleting messages for game ${gameId} in ${region}: ${error.message}`);
        return 0;
    }
}

app.post('/api/update-price-message', async (req, res) => {
    const { game_id, region, prices, channel_id, header, footer } = req.body;

    if (!game_id || !region || !channel_id) {
        console.error('Missing required fields', req.body);
        return res.status(400).json({ success: false, message: 'Missing required fields' });
    }

    if (!prices || prices.length === 0) {
        console.log(`No price data for game ${game_id} in ${region}. Skipping Discord update.`);
        return res.json({ success: true, message: 'No price data available. Discord update skipped.' });
    }

    const key = `${game_id}:${region}`;
    if (processing.has(key)) {
        console.log(`Already processing game ${game_id} in ${region}. Skipping duplicate request.`);
        return res.status(429).json({ success: false, message: 'Request already in progress' });
    }
    processing.add(key);

    try {
        const channel = await client.channels.fetch(channel_id);
        if (!channel || !channel.isTextBased()) {
            console.error(`Channel ${channel_id} not found or not a text channel for game ${game_id} in ${region}`);
            return res.status(404).json({ success: false, message: 'Channel not found or not a text channel' });
        }

        const permissions = channel.permissionsFor(client.user);
        if (!permissions.has(['SEND_MESSAGES', 'MANAGE_MESSAGES', 'VIEW_CHANNEL'])) {
            console.error(`Missing permissions for game ${game_id} in ${region}:`, {
                sendMessages: permissions.has('SEND_MESSAGES'),
                manageMessages: permissions.has('MANAGE_MESSAGES'),
                viewChannel: permissions.has('VIEW_CHANNEL'),
            });
            return res.status(403).json({ success: false, message: 'Bot lacks required permissions' });
        }

        await deleteExistingMessages(channel, game_id, region, header, footer);

        const newMessage = await channel.send(formatPriceMessage(game_id, region, prices, header, footer));
        console.log(`Created new message ${newMessage.id} for game ${game_id} in ${region}`);

        try {
            const response = await axios.post(`${LARAVEL_API_URL}/api/price-messages/update`, {
                game_id,
                region,
                message_id: newMessage.id,
            });
            console.log(`Updated message_id for game ${game_id} in ${region}: ${response.data.message}`);
        } catch (error) {
            console.error(`Error updating message_id in backend for game ${game_id} in ${region}: ${error.message}`);
            if (error.response) {
                console.error(`Response: ${JSON.stringify(error.response.data)}`);
            }
        }

        return res.json({ 
            success: true, 
            message: 'Price message created successfully', 
            message_id: newMessage.id 
        });
    } catch (error) {
        console.error(`Error processing price message for game ${game_id} in ${region}: ${error.message}`);
        return res.status(500).json({ 
            success: false, 
            message: `Error processing price message: ${error.message}` 
        });
    } finally {
        processing.delete(key);
    }
});

app.post('/api/send-message', async (req, res) => {
    const { recipient_id, message } = req.body;

    if (!recipient_id || !message) {
        console.error('Missing recipient_id or message', req.body);
        return res.status(400).json({ success: false, message: 'Missing recipient_id or message' });
    }

    try {
        const user = await client.users.fetch(recipient_id);
        await user.send(message);
        console.log(`Sent DM to ${recipient_id}: ${message}`);
        return res.json({ success: true, message: 'Message sent successfully' });
    } catch (error) {
        console.error(`Error sending DM to ${recipient_id}: ${error.message}`);
        return res.status(500).json({ success: false, message: `Failed to send message: ${error.message}` });
    }
});

app.post('/api/get-discord-id-from-username', async (req, res) => {
    const { username } = req.body;

    if (!username) {
        console.error('Missing username', req.body);
        return res.status(400).json({ success: false, message: 'Missing username' });
    }

    try {
        console.log(`Resolving Discord username: ${username}`);
        const guild = await client.guilds.fetch(GUILD_ID);
        const members = await guild.members.search({ query: username });
        const member = members.find(m => m.user.username.toLowerCase() === username.toLowerCase());

        if (member) {
            console.log(`Resolved ${username} to ID ${member.user.id}`);
            return res.json({ success: true, discord_id: member.user.id });
        }

        console.warn(`Member not found for username: ${username} in guild ${GUILD_ID}`);
        return res.status(404).json({ success: false, message: 'Member not found in guild' });
    } catch (error) {
        console.error(`Error resolving Discord username ${username}: ${error.message}`);
        return res.status(500).json({ success: false, message: `Failed to resolve username: ${error.message}` });
    }
});

// Utility function to retry Axios requests
async function axiosWithRetry(config, retries = 3, delay = 1000) {
    try {
        return await axios(config);
    } catch (error) {
        if (
            retries > 0 &&
            (
                error.code === 'ETIMEDOUT' ||
                error.code === 'ECONNABORTED' ||
                error.code === 'ECONNRESET' ||
                error.code === 'ENOTFOUND' ||
                (error.response && (error.response.status >= 500 || error.response.status === 429))
            )
        ) {
            console.error(`Request failed. Retrying in ${delay}ms... (${retries} retries left)`);
            await new Promise(resolve => setTimeout(resolve, delay));
            return axiosWithRetry(config, retries - 1, delay * 2);
        }
        console.error('Request failed with error:', error);
        throw error;
    }
}

// Helper function to safely send DMs
async function safeSendDM(user, content) {
    if (!user) {
        console.error('Cannot send DM to undefined user');
        return false;
    }
    try {
        await user.send(content);
        return true;
    } catch (error) {
        console.error(`Failed to send DM to ${user.tag || 'user'}: ${error.message}`);
        return false;
    }
}

// Handle !balance command
async function handleBalanceCommand(message) {
    const user = message.author;
    const discordId = user.id;

    try {
        // Delete the user's message
        await message.delete();
        console.log(`Deleted !balance message from ${user.tag} in channel ${message.channel.id}`);
    } catch (error) {
        console.error(`Failed to delete !balance message from ${user.tag}: ${error.message}`);
        // Proceed even if deletion fails to ensure the command executes
    }

    try {
        const response = await axiosWithRetry({
            method: 'post',
            url: `${LARAVEL_API_URL}/api/get-balance`,
            data: { discord_id: discordId },
            timeout: 10000,
        }, 3, 1000);

        if (response.data.success) {
            const balance = response.data.balance;
            const embed = new EmbedBuilder()
                .setColor('#0099ff')
                .setTitle('💰 Your Balance')
                .setDescription(`Here’s your current balance, ${user.username}!`)
                .addFields({ name: '🟢 Balance', value: `**${balance.toLocaleString()}** Toman`, inline: true })
                .setTimestamp()
                .setFooter({ text: 'Gold Bot', iconURL: user.displayAvatarURL() });

            const sentDM = await safeSendDM(user, { embeds: [embed] });
            if (sentDM && message.channel.type !== 'DM') {
                await message.reply(`✅ Balance details sent to your DMs.`);
            }
        } else {
            await message.reply(`❌ ${response.data.message || 'Failed to retrieve balance.'}`);
        }
    } catch (error) {
        console.error(`Error retrieving balance for ${discordId}:`, error.message);
        if (error.response) {
            console.error(`Response: ${JSON.stringify(error.response.data)}`);
        }
        await message.reply('❌ An error occurred while retrieving your balance. Please try again later.');
    }
}
// Handle !setbank command
async function handleSetBankCommand(message) {
    try {
        // Delete the user's message
        await message.delete();
        console.log(`Deleted !setbank message from ${message.author.tag} in channel ${message.channel.id}`);
    } catch (error) {
        console.error(`Failed to delete !setbank message from ${message.author.tag}: ${error.message}`);
        // Proceed even if deletion fails to ensure the command executes
        await safeSendDM(message.author, '⚠️ Could not delete your message. Please ensure it’s sent in a private channel for security.');
    }

    if (message.content.includes('\n')) {
        return safeSendDM(message.author, '❌ Invalid format! Use: `!setbank [IBAN] [Card Number] [Full Name]` on a single line.');
    }

    const input = message.content.slice(8).trim();
    const match = input.match(/^(\S+)\s+(\S+)\s+(.+)$/);
    if (!match) {
        return safeSendDM(message.author, '❌ Invalid format! Use: `!setbank [IBAN] [Card Number] [Full Name]` with exactly 3 parts.');
    }

    const [, rawIban, cardNumber, fullName] = match;
    const iban = rawIban.toUpperCase().replace(/^IR-?/, '');
    if (!/^[0-9]{24}$/.test(iban)) {
        return safeSendDM(message.author, '❌ Invalid IBAN! Must contain 24 digits after IR prefix.');
    }

    if (!/^\d{16}$/.test(cardNumber)) {
        return safeSendDM(message.author, '❌ Invalid card number! Must be 16 digits.');
    }

    if (!fullName || fullName.length < 2) {
        return safeSendDM(message.author, '❌ Full name must be at least 2 characters.');
    }

    const bankData = {
        discord_id: message.author.id,
        iban: `IR${iban}`,
        card_number: cardNumber,
        full_name: fullName,
    };

    try {
        const response = await axiosWithRetry({
            method: 'post',
            url: `${LARAVEL_API_URL}/api/set-bank-details`,
            data: bankData,
            timeout: 10000,
        }, 3, 1000);

        const embed = new EmbedBuilder()
            .setColor(response.data.success ? '#00FF00' : '#FF0000')
            .setTitle('🏦 Bank Account Update')
            .addFields(
                { name: 'IBAN', value: `\`${bankData.iban}\``, inline: true },
                { name: 'Card Number', value: `\`•••• ${cardNumber.slice(-4)}\``, inline: true },
                { name: 'Account Name', value: `\`${fullName}\`` }
            )
            .setFooter({ text: 'Verification may take 24-48 hours', iconURL: client.user.displayAvatarURL() })
            .setTimestamp();

        const sentDM = await safeSendDM(message.author, {
            content: response.data.message || (response.data.success ? 'Bank details updated successfully!' : 'Failed to update bank details.'),
            embeds: [embed]
        });

        if (sentDM && message.channel.type !== 'DM') {
            const sentMessage = await message.channel.send(`✅ ${message.author}, check your DMs for bank update confirmation!`);
            setTimeout(async () => {
                try {
                    await sentMessage.delete();
                } catch (error) {
                    console.error('Error deleting confirmation message:', error);
                }
            }, 5000);
        }
    } catch (error) {
        console.error(`Error updating bank details for ${message.author.id}:`, error.message);
        if (error.response) {
            console.error(`Response: ${JSON.stringify(error.response.data)}`);
        }
        await safeSendDM(message.author, '🔧 Error processing your bank details. Please try again later.');
    }
}

// Event listener for messages
client.on('messageCreate', async (message) => {
    if (message.author.bot) return;

    if (message.content.startsWith('!balance')) {
        await handleBalanceCommand(message);
    }

    if (message.content.startsWith('!setbank')) {
        await handleSetBankCommand(message);
    }
});

app.listen(port, () => {
    console.log(`Server running on port ${port}`);
});