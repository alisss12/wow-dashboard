const { ActionRowBuilder, ButtonBuilder, ButtonStyle } = require('discord.js');
const axios = require('axios');

module.exports = {
    data: {
        name: 'removemm',
        description: 'Removes selected players from an event.',
    },
    async execute(message) {
        const captainRole = message.guild.roles.cache.find(role => role.name === 'Captain');
        if (!captainRole || !message.member.roles.cache.has(captainRole.id)) {
            return message.reply('You need to have the "Captain" role to use this command.');
        }

        const mentions = message.mentions.users;

        if (mentions.size === 0 || mentions.size > 4) {
            return message.reply('You need to mention 1 to 4 users to remove from the event.');
        }

        const userIDs = mentions.map(user => user.id);

        try {
            const response = await axios.post('admin.game3rade.com/wowbot/raiderio/team/remove_event.php', {
                captainID: message.author.id,
                userIDs: userIDs,
            });

            if (response.data.status === 'success') {
                const removedUsers = mentions.map(user => user.username).join(', ');
                return message.channel.send(`Successfully removed ${removedUsers} from the event!`);
            } else {
                return message.reply(`Failed to remove users: ${response.data.message}`);
            }
        } catch (error) {
            console.error('Error removing users from the event:', error);
            return message.reply('An error occurred while removing users from the event.');
        }
    },
};
