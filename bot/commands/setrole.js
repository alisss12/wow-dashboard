// Add this line at the top of your file to import axios
const axios = require('axios');

const { PermissionsBitField } = require('discord.js');

module.exports = {
  data: {
    name: 'setrole',
    description: 'Assign roles to a user',
  },
  async execute(message) {
    // Checking permissions
    if (!message.member.permissions.has(PermissionsBitField.Flags.ManageRoles)) {
      return message.reply('You do not have permission to manage roles.');
    }
    
    // Fetch the user from the mention
    const user = message.mentions.users.first();
    if (!user) {
      return message.reply('Please mention a user to set their role.');
    }

    const commandContent = message.content.slice('!setrole'.length).trim();
    // Get the roles from the arguments
    const rolesToSet = commandContent.split(' ').slice(1).map(role => role.toLowerCase());

    // Check if provided roles are valid
    const validRoles = ['advertiser', 'dps', 'plate', 'leather', 'mail', 'tank', 'cloth', 'heal'];
    const rolesToAdd = rolesToSet.filter(role => validRoles.includes(role));

    if (rolesToAdd.length === 0) {
      return message.reply('No valid roles were specified.');
    }

    // Replace the handleDatabaseOperations function with sendRolesToPHPServer
    const sendRolesToPHPServer = async () => {
      try {
        const response = await axios.post('http://152.228.154.27/admin/register.php', {
          discord_id: user.id,
          roles: rolesToAdd.join(',')
        });

        // Check response status
        if (response.data.status === 'success') {
          console.log(`Roles successfully sent to the PHP server for user ID: ${user.id}`);
        } else {
          console.error('Failed to send roles to PHP server:', response.data);
        }
      } catch (error) {
        console.error('Error sending roles to PHP server:', error);
        throw new Error('Error sending roles to PHP server.');
      }
    };

    // Handle role assignment on Discord and update database
    try {
      // Find the Discord role objects
      const guildMember = await message.guild.members.fetch(user.id);
      const roleObjects = rolesToAdd.map(roleName => message.guild.roles.cache.find(role => role.name.toLowerCase() === roleName));
      const validRoleObjects = roleObjects.filter(role => !!role);

      // Add the roles on Discord
      await guildMember.roles.add(validRoleObjects);

      // Send the roles to the PHP server
      await sendRolesToPHPServer();

      // Reply to the message to confirm the roles have been set
      await message.reply(`Roles [${rolesToAdd.join(', ')}] have been successfully set for ${user.tag}.`);
      const promotionMessage = `Congratulations on your promotion! You've been assigned new roles: ${rolesToAdd.join(', ')}.`;

      try {
        const userDM = await user.send(promotionMessage);
        console.log(`Promotion message sent to ${user.tag}`);
      } catch (error) {
        console.error(`Failed to send promotion message to ${user.tag}:`, error);
      }
    } catch (error) {
      console.error('Error occurred while assigning roles:', error);
      await message.reply('An error occurred while setting roles.');
    }
  },
};