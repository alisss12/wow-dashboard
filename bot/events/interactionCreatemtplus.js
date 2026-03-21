const {
    ChannelType,
    ModalBuilder,
    TextInputBuilder,
    TextInputStyle,
    ActionRowBuilder,
    EmbedBuilder,
    ButtonBuilder,
    ButtonStyle,
} = require('discord.js');
const mysql = require('mysql2/promise');
const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '..', '.env') });

// MySQL Pool
const pool = mysql.createPool({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
    connectionLimit: process.env.DB_CONN_LIMIT || 10,
    waitForConnections: true,
    queueLimit: 0
});

// Fixed labels
async function getInputLabelsTeam() {
    return {
        input1: 'M+',
        input2: 'Armor Stack',
        input3: 'Key',
        input4: 'Cut',
        input5: 'Additional Notes'
    };
}

// Memory storages
const mtplusContext = new Map(); // Store step 1 selection: userId -> { selectedValue }
const activeMtplusOrders = new Map(); // Store open applications: messageId -> { advertiserId, applicants: [{userId, class, score, role...}] }

module.exports = {
    name: 'interactionCreatemtplus',
    async execute(interaction) {
        try {
            const userId = interaction.user.id;

            // ==========================================
            // 1. Initial Menu: Cancel & Region Selection
            // ==========================================
            if (interaction.isButton() && interaction.customId === 'cancel_mtplus_select') {
                await interaction.update({ content: 'Mythic+ Squad Boost order canceled.', components: [] });
                return;
            }

            if (interaction.isStringSelectMenu() && interaction.customId === 'select_mtplus_region') {
                const selectedValue = interaction.values[0];
                mtplusContext.set(userId, { selectedValue });

                const inputLabels = await getInputLabelsTeam();

                const modal = new ModalBuilder()
                    .setCustomId('mtplus_modal')
                    .setTitle(`${selectedValue === 'mtplus_us' ? 'USA' : 'EU'} MTPlus Boost Details`);

                ['input1', 'input2', 'input3', 'input4', 'input5'].forEach((input) => {
                    modal.addComponents(
                        new ActionRowBuilder().addComponents(
                            new TextInputBuilder()
                                .setCustomId(input)
                                .setLabel(inputLabels[input])
                                .setStyle(TextInputStyle.Short)
                                .setRequired(true)
                                .setMaxLength(100)
                        )
                    );
                });

                try {
                    await interaction.showModal(modal);
                } catch (error) {
                    console.error('Failed to show modal:', error);
                    if (!interaction.replied && !interaction.deferred) {
                        await interaction.reply({
                            content: 'Failed to open the form. Please try again or check your connection.',
                            ephemeral: true
                        }).catch(() => { });
                    }
                }
                return;
            }


            // ==========================================
            // 2. Modal Submission: Post the Order Header
            // ==========================================
            if (interaction.isModalSubmit() && interaction.customId === 'mtplus_modal') {
                await interaction.deferUpdate();

                const context = mtplusContext.get(userId);
                if (!context || !context.selectedValue) {
                    await interaction.followUp({ content: 'Session expired. Please start again.', ephemeral: true });
                    return;
                }

                const selectedRegionInfo = context.selectedValue === 'mtplus_us' ? 'USA' : 'EU';

                const inputValues = {
                    input1: interaction.fields.getTextInputValue('input1'),
                    input2: interaction.fields.getTextInputValue('input2'),
                    input3: interaction.fields.getTextInputValue('input3'),
                    input4: interaction.fields.getTextInputValue('input4'),
                    input5: interaction.fields.getTextInputValue('input5')
                };
                const inputLabels = await getInputLabelsTeam();
                const guild = interaction.guild;

                const channelName = selectedRegionInfo === 'USA' ? 'team-boost-usa' : 'team-boost-eu';
                const teamBoostChannel = guild.channels.cache.find(ch => ch.name.toLowerCase() === channelName);
                if (!teamBoostChannel) {
                    await interaction.followUp({ content: `Channel "${channelName}" not found.`, ephemeral: true });
                    return;
                }

                // Build Buttons
                const applyRow = new ActionRowBuilder().addComponents(
                    new ButtonBuilder()
                        .setCustomId('apply_mtplus_squad')
                        .setLabel('✋ Apply with Mythic+ Squad')
                        .setStyle(ButtonStyle.Success)
                );

                const advertiserRow = new ActionRowBuilder().addComponents(
                    new ButtonBuilder()
                        .setCustomId('review_mtplus_offers')
                        .setLabel('📋 Review Applications')
                        .setStyle(ButtonStyle.Primary),
                    new ButtonBuilder()
                        .setCustomId('cancel_mtplus_order')
                        .setLabel('🗑️ Delete Post')
                        .setStyle(ButtonStyle.Danger)
                );

                const embed = new EmbedBuilder()
                    .setTitle('🚀 New Mythic+ Squad Run')
                    .setDescription(`**Looking for Boosters!** Click an apply button below to send your offer directly to the Advertiser. Your Raider.IO score will be attached automatically.\n\n**Run Details:**`)
                    .addFields(
                        { name: inputLabels.input1, value: inputValues.input1, inline: true },
                        { name: inputLabels.input2, value: inputValues.input2, inline: true },
                        { name: inputLabels.input3, value: inputValues.input3, inline: true },
                        { name: inputLabels.input4, value: inputValues.input4, inline: true },
                        { name: inputLabels.input5, value: inputValues.input5, inline: false },
                        { name: 'Region', value: selectedRegionInfo, inline: false },
                        { name: 'Ordered By', value: `<@${userId}>`, inline: false }
                    )
                    .setThumbnail('https://i.imgur.com/YVbNLVA.jpeg')
                    .setColor('#8A2BE2'); // Purple color distinct from random !mplus

                try {
                    const orderMessage = await teamBoostChannel.send({
                        content: '@here **A new Mythic+ Squad is recruiting!**',
                        embeds: [embed],
                        components: [applyRow, advertiserRow]
                    });

                    // Register this application into memory
                    activeMtplusOrders.set(orderMessage.id, {
                        advertiserId: userId,
                        applicants: []
                    });

                    mtplusContext.delete(userId);
                    // Re-edit original command msg so it clears the ephemeral dropdown interface
                    await interaction.editReply({ content: `Order posted successfully in <#${teamBoostChannel.id}>`, components: [] });

                } catch (e) {
                    console.error('Error sending order msg: ', e);
                    await interaction.followUp({ content: 'Could not post the order to the channel.', ephemeral: true });
                }
                return;
            }

            // ==========================================
            // 3. Application System: Sending Offers
            // ==========================================
            if (interaction.isButton() && interaction.customId === 'apply_mtplus_squad') {
                await interaction.deferReply({ ephemeral: true });
                const orderData = activeMtplusOrders.get(interaction.message.id);

                if (!orderData) {
                    return await interaction.editReply({ content: '❌ This Mythic+ run is no longer accepting applications or has been deleted.' });
                }

                if (userId === orderData.advertiserId) {
                    return await interaction.editReply({ content: '❌ You are the Advertiser! You cannot apply to your own run.' });
                }

                // Check if already applied
                const existingApplicant = orderData.applicants.find(a => a.userId === userId);
                if (existingApplicant) {
                    return await interaction.editReply({ content: `❌ You have already sent a squad application to this run!` });
                }

                // Check Database for their active squad
                let rows = [];
                try {
                    const connection = await pool.getConnection();

                    // Ensure table exists before querying
                    await connection.execute(`
                        CREATE TABLE IF NOT EXISTS active_mplus_squads (
                            advertiser_id VARCHAR(255) PRIMARY KEY,
                            squad_json TEXT,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                        )
                    `);

                    [rows] = await connection.execute('SELECT squad_json FROM active_mplus_squads WHERE advertiser_id = ?', [userId]);
                    connection.release();
                } catch (dbError) {
                    console.error('Squad Verify Error:', dbError);
                    return await interaction.editReply({ content: '❌ A database error occurred while verifying your squad.' });
                }

                if (rows.length === 0) {
                    return await interaction.editReply({ content: '❌ **No Active Squad Found!**\nYou must first form a squad using the `!team <players>` command before you can apply to orders as a Booster Leader.' });
                }

                let savedSquad;
                try {
                    savedSquad = JSON.parse(rows[0].squad_json);
                } catch (e) {
                    return await interaction.editReply({ content: '❌ Your saved squad data is corrupt. Please build a new squad with `!team`.' });
                }

                // Push them to the running application list
                orderData.applicants.push({
                    userId,
                    discordTag: interaction.user.username,
                    squadDetails: savedSquad.verboseDetails, // The verbose strings saved from !team
                    memberIds: savedSquad.members.map(m => m.id),
                    appliedAt: new Date()
                });

                return await interaction.editReply({ content: `✅ **Squad Application Sent!**\nThe Advertiser will now be able to review your full squad's Raider.IO scores.` });
            }

            // ==========================================
            // 4. Advertiser Management: Review Offers
            // ==========================================
            if (interaction.isButton() && interaction.customId === 'review_mtplus_offers') {
                const orderData = activeMtplusOrders.get(interaction.message.id);
                if (!orderData) {
                    return await interaction.reply({ content: 'Run data lost.', ephemeral: true });
                }

                if (orderData.advertiserId !== userId && !interaction.member.roles.cache.some(r => r.name === 'Admin')) {
                    return await interaction.reply({ content: 'Only the Advertiser who created this post can review applications.', ephemeral: true });
                }

                if (orderData.applicants.length === 0) {
                    return await interaction.reply({ content: 'No boosters have applied to your run yet.', ephemeral: true });
                }

                // Build a clean embed list of all applying SQUADS
                const applicantList = orderData.applicants.map((a, i) => {
                    return `**🛡️ Offer #${i + 1} - Booster Leader: <@${a.userId}>**\n${a.squadDetails.join('\n')}\n──────────────────────────`;
                });

                const offersEmbed = new EmbedBuilder()
                    .setTitle('📋 Booster Squad Applications')
                    .setDescription(`Here are the full Squads that have offered to boost your run. Contact the Booster Leader to invite them!\n\n` + applicantList.join('\n\n'))
                    .setColor('#00fa9a')
                    .setFooter({ text: 'Mythic+ Squad Offers Hub' })
                    .setTimestamp();

                return await interaction.reply({ embeds: [offersEmbed], ephemeral: true });
            }

            // ==========================================
            // 5. Advertiser Management: Cancel/Delete Post
            // ==========================================
            if (interaction.isButton() && interaction.customId === 'cancel_mtplus_order') {
                const orderData = activeMtplusOrders.get(interaction.message.id);
                if (!orderData) {
                    return await interaction.reply({ content: 'Run data lost.', ephemeral: true });
                }

                if (orderData.advertiserId !== userId && !interaction.member.roles.cache.some(r => r.name === 'Admin')) {
                    return await interaction.reply({ content: 'Only the Advertiser who created this post can delete it.', ephemeral: true });
                }

                activeMtplusOrders.delete(interaction.message.id);
                try {
                    await interaction.message.delete();
                    await interaction.reply({ content: 'Order post successfully deleted.', ephemeral: true });
                } catch (e) {
                    await interaction.reply({ content: 'Order has already been deleted.', ephemeral: true });
                }
            }

        } catch (error) {
            console.error('Error in interactionCreatemtplus:', error);
            if (!interaction.replied && !interaction.deferred) {
                await interaction.reply({ content: 'An unexpected error occurred.', ephemeral: true }).catch(() => { });
            }
        }
    },
};
