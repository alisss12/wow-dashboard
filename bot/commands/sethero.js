const axios = require('axios');
const { PermissionsBitField } = require('discord.js');

module.exports = {
  data: {
    name: 'sethero',
    description: 'Set hero information including name, realm, and faction',
  },
  async execute(message) {
    if (!message.member.permissions.has(PermissionsBitField.Flags.ManageRoles)) {
      return message.reply('You do not have permission to manage hero information.');
    }

  const user = message.mentions.users.first();
    if (!user) {
      return message.reply('Please mention a user to set their hero information.');
    }

      const commandContent = message.content.slice('!sethero'.length).trim();
    const commandArgs = commandContent.split(' '); 
	
 if (commandArgs.length < 4) {
      return message.reply('Invalid hero information format. Please provide name, realm, and faction.');
    }


commandArgs.shift(); // Remove the command itself (!sethero)
const heroName = commandArgs[0];
const heroRealm = commandArgs[commandArgs.length - 2];
const heroFaction = commandArgs[commandArgs.length - 1].toLowerCase();

    const sendHeroToDatabase = async () => {
      try {
        const response = await axios.post('http://152.228.154.27/admin/sethero.php', {
          discord_id: user.id,
          name: heroName,
          realm: heroRealm,
          faction: heroFaction
        });

        if (response.data.status === 'success') {
          console.log(`Hero information successfully sent to the database for user ID: ${user.id}`);
        } else {
          console.error('Failed to send hero information to the database:', response.data);
        }
      } catch (error) {
        console.error('Error sending hero information to the database:', error);
        throw new Error('Error sending hero information to the database.');
      }
    };

    try {
      await sendHeroToDatabase();
      await message.reply(`Hero information [Name: ${heroName}, Realm: ${heroRealm}, Faction: ${heroFaction}] has been successfully set for ${user.tag}.`);
      const notificationMessage = `Your hero information has been updated! Name: ${heroName}, Realm: ${heroRealm}, Faction: ${heroFaction}.`;

      try {
        const userDM = await user.send(notificationMessage);
        console.log(`Notification message sent to ${user.tag}`);
      } catch (error) {
        console.error(`Failed to send notification message to ${user.tag}:`, error);
      }
    } catch (error) {
      console.error('Error occurred while setting hero information:', error);
      await message.reply('An error occurred while setting hero information.');
    }
  },
};