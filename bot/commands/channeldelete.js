const { Client, ChannelType } = require('discord.js');


// commands/channeldelete.js
module.exports = {
  data: {
    name: 'channeldelete',
    description: 'Delete a channel by its name.',
  },
  async execute(message) {
    try {
      // Check if the command is invoked in a guild (server)
      if (!message.guild) {
        await message.reply('This command can only be used in a server.');
        return;
      }

      // Check if the user has the necessary permissions to delete channels
      if (!message.member.permissions.has('MANAGE_CHANNELS')) {
        await message.reply('You do not have permission to delete channels.');
        return;
      }

      // Get the name of the channel to delete from the command content
      const channelName = message.content.split(' ').slice(1).join(' ');

      // Find the channel by its name
      const channelToDelete = message.guild.channels.cache.find(channel => channel.name === channelName);

      if (!channelToDelete) {
        await message.reply('Channel not found.');
        return;
      }

      // Delete the channel
      await channelToDelete.delete();
      await message.reply(`Channel "${channelName}" has been deleted.`);
    } catch (error) {
      console.error('Error deleting channel:', error);
      await message.reply('An error occurred while deleting the channel.');
    }
  },
};
