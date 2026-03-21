const { ActionRowBuilder, ButtonBuilder, ButtonStyle } = require('discord.js');
const axios = require('axios');

module.exports = {
    data: {
        name: 'chp',
        description: 'Registers selected players for an event.',
    },
    async execute(message) {
        const captainRole = message.guild.roles.cache.find(role => role.name === 'Captain');
        if (!captainRole || !message.member.roles.cache.has(captainRole.id)) {
            return message.reply('You need to have the "Captain" role to use this command.');
        }

        const args = message.content.split(' ').slice(1); // Split the message content and ignore the command part
        const mentions = message.mentions.users;

        if (mentions.size === 0 || mentions.size > 4) {
            return message.reply('You need to mention 1 to 4 users to register for the event.');
        }

        if (args.length < 2) {
            return message.reply('You need to specify a role and an event name.');
        }

        const role = args.pop().toUpperCase(); // Get the last argument as the role and convert to uppercase
        const eventName = args.slice(1).join(' '); // The rest are the event name

        const validRoles = ['DPS', 'PLATE', 'LEATHER', 'TANK', 'CLOTH', 'HEAL'];
        if (!validRoles.includes(role)) {
            return message.reply(`Invalid role. Valid roles are: ${validRoles.join(', ')}`);
        }


        // Register users for the event
        const userIDs = mentions.map(user => user.id);
        try {
            const response = await axios.post('http://admin.game3rade.com/wowbot/raiderio/team/register_event.php', {
                captainID: message.author.id,
                userIDs: userIDs,
                eventDetails: {
                    eventName: eventName,
                    eventDate: 'Event Date/Time', // Update this as needed
                    role: role,
                }
            });

            if (response.data.status === 'success') {
                const registeredUsers = mentions.map(user => user.username).join(', ');

                // Send messages to users only if registration is successful
                const eventMessage = `Hello! You've been selected to participate in our upcoming event "${eventName}" as a ${role}. Do you accept?`;
                const promises = [];

                mentions.forEach(user => {
                    const row = createAcceptDeclineRow(user.id);
                    promises.push(
                        user.send({ content: eventMessage, components: [row] })
                            .then(() => ({ user, accepted: true }))
                            .catch(() => ({ user, accepted: false }))
                    );
                });

                const results = await Promise.all(promises);
                const acceptedUsers = results.filter(result => result.accepted).map(result => result.user);

                if (acceptedUsers.length === 0) {
                    return message.reply('No users accepted the invitation.');
                }

                return message.channel.send(`Successfully registered ${registeredUsers} for the event "${eventName}" as ${role}!`);
            } else {
                return message.reply(`Failed to register users: ${response.data.message}`);
            }
        } catch (error) {
            console.error('Error registering users for the event:', error);
            return message.reply('An error occurred while registering users for the event.');
        }
    },
};

function createAcceptDeclineRow(userId) {
    const acceptButton = new ButtonBuilder()
        .setCustomId(`accept_${userId}`)
        .setLabel('Accept')
        .setStyle(ButtonStyle.Success);

    const declineButton = new ButtonBuilder()
        .setCustomId(`decline_${userId}`)
        .setLabel('Decline')
        .setStyle(ButtonStyle.Danger);

    const row = new ActionRowBuilder()
        .addComponents(acceptButton, declineButton);

    return row;
}
