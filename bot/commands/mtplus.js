const { StringSelectMenuBuilder, ActionRowBuilder, ButtonBuilder, ButtonStyle } = require('discord.js');
const mysql = require('mysql2/promise');
const dbConfig = require('../config');

module.exports = {
    data: {
        name: 'mtplus',
        description: 'Create a Mythic+ Team Boost with the Booster Application system.',
    },
    async execute(message) {
        try {
            if (!message.guild) {
                return await message.reply({ content: 'This command can only be used in a server.', ephemeral: true });
            }

            const advertiserRole = message.guild.roles.cache.find((role) => role.name === 'Advertiser');
            if (!advertiserRole || !message.member.roles.cache.has(advertiserRole.id)) {
                await message.reply('You need to have the "Advertiser" role to use this command.');
                return;
            }

            const selectMenu = new StringSelectMenuBuilder()
                .setCustomId('select_mtplus_region')
                .setPlaceholder('Select MTPlus Team Region')
                .addOptions([
                    { label: 'Team EU', value: 'mtplus_eu' },
                    { label: 'Team US', value: 'mtplus_us' },
                ]);

            const cancelButton = new ButtonBuilder()
                .setCustomId('cancel_mtplus_select')
                .setLabel('Cancel')
                .setStyle(ButtonStyle.Secondary);

            const row1 = new ActionRowBuilder().addComponents(selectMenu);
            const row2 = new ActionRowBuilder().addComponents(cancelButton);

            await message.reply({
                content: '**Create a Mythic+ Squad Boost**\nPlease select the region for this order:',
                components: [row1, row2],
                ephemeral: true, // Only advertiser sees this initial menu
            });
        } catch (error) {
            console.error('Error executing mtplus command:', error);
            await message.reply({ content: 'An error occurred.', ephemeral: true }).catch(() => { });
        }
    },
};
