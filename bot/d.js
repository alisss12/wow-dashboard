const axios = require('axios');
const settings = require('./settings.json');
const fs = require('fs').promises;
const path = require('path');
const { Client, Collection, GatewayIntentBits, Events, ButtonBuilder, ButtonStyle, ActionRowBuilder, ModalBuilder, TextInputBuilder, TextInputStyle } = require('discord.js');

const client = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMessages,
        GatewayIntentBits.GuildMessageReactions,
        GatewayIntentBits.GuildMembers,
        GatewayIntentBits.GuildMessageTyping,
        GatewayIntentBits.DirectMessages,
        GatewayIntentBits.DirectMessageReactions,
        GatewayIntentBits.DirectMessageTyping,
        GatewayIntentBits.MessageContent,
    ],
});

client.commands = new Collection();
client.events = new Collection();

async function loadCommands() {
    client.commands.clear();
    const commandsPath = path.join(__dirname, 'commands');
    try {
        const commandFiles = await fs.readdir(commandsPath);
        for (const file of commandFiles) {
            if (file.endsWith('.js')) {
                const filePath = path.join(commandsPath, file);
                const command = require(filePath);
                if ('data' in command && 'execute' in command) {
                    client.commands.set(command.data.name, command);
                    console.log(`Command loaded: ${command.data.name}`);
                } else {
                    console.warn(`[WARNING] The command at ${filePath} is missing a required "data" or "execute" property.`);
                }
            }
        }
    } catch (error) {
        console.error('Error loading commands:', error);
    }
}

async function loadEvents() {
    const eventsPath = path.join(__dirname, 'events');
    try {
        const eventFiles = await fs.readdir(eventsPath);
        for (const file of eventFiles) {
            if (file.endsWith('.js')) {
                const filePath = path.join(eventsPath, file);
                const event = require(filePath);
                if (event.name && event.execute) {
                    client.events.set(event.name, event);
                    console.log(`Event loaded: ${event.name}`);
                } else {
                    console.warn(`[WARNING] The event at ${filePath} is missing a required "name" or "execute" property.`);
                }
            }
        }
    } catch (error) {
        console.error('Error loading events:', error);
    }
}

client.once('ready', async () => {
    console.log('Ready!');
    await loadCommands();
    await loadEvents();
});

// General interaction handler
client.on(Events.InteractionCreate, async (interaction) => {
    const event = client.events.get('interactionCreate');
    if (event) {
        console.log('InteractionCreate event found');
        await event.execute(interaction);
    } else {
        console.error('Event "interactionCreate" not found in client.events');
    }
});

// Team-specific interaction handler
client.on(Events.InteractionCreate, async (interaction) => {
    const event = client.events.get('interactionCreateteam');
    if (event) {
        console.log('interactionCreateteam event found');
        await event.execute(interaction);
    } else {
        console.error('Event "interactionCreateteam" not found in client.events');
    }
});

// MTPlus Application interaction handler
client.on(Events.InteractionCreate, async (interaction) => {
    const event = client.events.get('interactionCreatemtplus');
    if (event) {
        console.log('interactionCreatemtplus event found');
        await event.execute(interaction);
    }
});

client.on('messageCreate', async (message) => {
    if (message.author.bot) return;

    if (message.content.startsWith('!')) {
        const args = message.content.slice(1).trim().split(/ +/);
        const commandName = args.shift().toLowerCase();
        const command = client.commands.get(commandName);
        if (!command) return;
        try {
            await command.execute(message, args);
        } catch (error) {
            console.error(`Error in command ${commandName}:`, error);
            await message.reply('There was an error executing that command!');
        }
        return;
    }

    const rioRegex = /https?:\/\/raider\.io\/characters\/([a-z]{2})\/([^/\s]+)\/([^?\s]+)/i;
    const urlMatch = message.content.match(rioRegex);
    if (!urlMatch) return;

    const region = urlMatch[1].toLowerCase();
    const realm = urlMatch[2];
    const rawCharacterName = urlMatch[3];
    const encodedCharacterName = encodeURIComponent(rawCharacterName);
    const normalizedUrl = `https://raider.io/characters/${region}/${realm}/${encodedCharacterName}`;

    const userId = message.author.id;
    const key = `rio:${userId}:${normalizedUrl}`;

    if (client.processing?.has(key)) return;
    client.processing ??= new Set();
    client.processing.add(key);

    try {
        await message.react('⌛');

        const saveRes = await axios.post('http://152.228.154.27/wowbot/raiderio/riader/info.php', {
            discord_id: userId,
            url: normalizedUrl
        });

        if (!saveRes.data.success) {
            throw new Error(saveRes.data.error || 'Unknown error');
        }

        const rankRes = await axios.post('http://152.228.154.27/wowbot/raiderio/riader/hero_rank.php', {
            data: ['DPS', 'HEALING', 'Tank'].map(r => ({
                discord_id: userId,
                active_spec_role: r
            }))
        });

        const ranks = Array.isArray(rankRes.data) ? rankRes.data : [];

        let dm = `**Raider.IO Registration Successful!**\n\n`;
        dm += `**Character:** ${saveRes.data.name}\n`;
        dm += `**Realm:** ${saveRes.data.realm}\n`;
        dm += `**Class:** ${saveRes.data.class}\n`;
        dm += `**Best Spec:** ${saveRes.data.active_spec_role || 'Unknown'}\n`;
        dm += `**Link:** ${normalizedUrl}\n\n`;
        dm += `**Your M+ Ranks**\n`;

        let rolesAssigned = 0;

        // Class role (WoW class color)
        const classRole = saveRes.data.class;
        if (classRole) {
            await assignRole(message.guild, message.author, classRole, 'class');
            rolesAssigned++;
        }

        // Armor role (Plate/Cloth/etc.)
        const armorRole = saveRes.data.role;
        if (armorRole && armorRole !== 'Unknown') {
            await assignRole(message.guild, message.author, armorRole, 'armor');
            rolesAssigned++;
        }

        // Spec role (DPS/HEALING/Tank) - colored
        const specRole = saveRes.data.active_spec_role;
        if (specRole && ['DPS', 'HEALING', 'Tank'].includes(specRole)) {
            await assignRole(message.guild, message.author, specRole, 'spec');
            rolesAssigned++;
        }

        // Rank roles - use color from database!
        for (const r of ranks) {
            const score = Math.round(r.score || 0);
            if (r.rank_level > 0) {
                const roleName = `${r.active_spec_role}${r.rank_level * 1000}`;
                const rankColor = r.color || '#00ff00'; // from DB
                await assignRole(message.guild, message.author, roleName, 'rank', rankColor);
                dm += `• ${r.active_spec_role} → **${roleName}** (${score})\n`;
                rolesAssigned++;
            } else {
                dm += `• ${r.active_spec_role} → ${score} (no rank yet)\n`;
            }
        }

        dm += `\n**${rolesAssigned} role(s) assigned!** (class, armor, spec, and ranks)`;

        await message.author.send(dm);
        await message.react('✅');

    } catch (error) {
        console.error('Raider.IO Error:', error.message || error);

        let userMsg = '**Registration failed.**';
        if (error.message?.includes('already registered')) {
            userMsg = '**This character is already registered by another booster!**';
        } else {
            userMsg += `\nError: ${error.message || 'Unknown error'}`;
        }

        try { await message.react('❌'); } catch { }
        try { await message.author.send(userMsg); } catch { }
    } finally {
        client.processing.delete(key);
    }
});

// Updated assignRole with custom color support
async function assignRole(guild, member, roleName, roleType = 'rank', customColor = null) {
    if (!roleName || typeof roleName !== 'string') return;

    const colorMap = {
        // Class colors
        'Death Knight': '#C41E3A', 'Demon Hunter': '#A330C9', 'Druid': '#FF7C0A',
        'Evoker': '#33937F', 'Hunter': '#AAD372', 'Mage': '#3FC7EB', 'Monk': '#00FF98',
        'Paladin': '#F48CBA', 'Priest': '#FFFFFF', 'Rogue': '#FFF468',
        'Shaman': '#0070DD', 'Warlock': '#8788EE', 'Warrior': '#C69B6D',

        // Armor
        'Plate': '#B8860B', 'Mail': '#8B7355', 'Leather': '#A0522D', 'Cloth': '#9370DB',

        // Spec
        'DPS': '#FF4444', 'HEALING': '#44FF44', 'Tank': '#4444FF',
    };

    let color = customColor || '#00ff00'; // Use DB color for ranks, else default

    if (!customColor && roleType === 'class' && colorMap[roleName]) color = colorMap[roleName];
    if (!customColor && roleType === 'armor' && colorMap[roleName]) color = colorMap[roleName];
    if (!customColor && roleType === 'spec' && colorMap[roleName]) color = colorMap[roleName];

    try {
        let role = guild.roles.cache.find(r => r.name === roleName);
        if (!role) {
            role = await guild.roles.create({
                name: roleName,
                color: color,
                reason: `Automatic ${roleType} role from Raider.IO`
            });
            console.log(`Created role: ${roleName} (Color: ${color})`);
        }

        const guildMember = await guild.members.fetch(member.id);
        if (!guildMember.roles.cache.has(role.id)) {
            await guildMember.roles.add(role);
            console.log(`Assigned ${roleName} to ${member.tag}`);
        }
    } catch (err) {
        console.error('Role assign error:', err.message);
    }
}

// === ALL ORIGINAL FUNCTIONS (kept as-is) ===
async function getRaiderIORank(url, discordId) {
    try {
        const response = await axios.post('http://152.228.154.27/wowbot/raiderio/riader/info.php', { discord_id: discordId, url });
        if (response.data.success) {
            return response.data.success;
        } else {
            console.error('Error in PHP response:', response.data.error);
            return null;
        }
    } catch (error) {
        console.error('Error fetching from PHP server:', error);
        return null;
    }
}

async function sendHeroRankInfoToPHP(data) {
    const phpEndpoint = 'http://152.228.154.27/wowbot/raiderio/riader/hero_rank.php';
    try {
        const response = await axios.post(phpEndpoint, { data });
        console.log('Hero Rank PHP API Response:', response.data);
        return response.data;
    } catch (error) {
        console.error('Error calling Hero Rank PHP API:', error.message);
        throw error;
    }
}

async function assignRoleToUser(guild, user, roleName) {
    try {
        const member = await guild.members.fetch(user.id);
        if (!member) return;
        let role = guild.roles.cache.find(role => role.name === roleName);
        if (!role) {
            role = await guild.roles.create({
                name: roleName,
                permissions: [],
                reason: 'Role created for Raider.IO rank',
            });
        }
        await member.roles.add(role);
    } catch (error) {
        console.error(`Error assigning role ${roleName}:`, error);
    }
}

function createInfoButtonRow(status) {
    const infoButton = new ButtonBuilder()
        .setCustomId('info')
        .setLabel(`Status: ${status}`)
        .setStyle(ButtonStyle.Secondary)
        .setDisabled(true);
    return new ActionRowBuilder().addComponents(infoButton);
}

async function updateEventRegistrationStatus(userId, status, eventId) {
    try {
        const response = await axios.post('http://152.228.154.27/wowbot/raiderio/team/update_event_status.php', {
            user_id: userId,
            status: status,
            event_id: eventId,
        });
        console.log('PHP API Response:', response.data);
    } catch (error) {
        console.error('Error calling PHP API:', error.message);
    }
}

async function startBot() {
    try {
        await client.login(settings.discord_token);
        await loadCommands();
    } catch (error) {
        console.error('Error logging in or loading commands:', error);
    }
}

startBot();