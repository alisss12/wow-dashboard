const { Client, PermissionsBitField } = require('discord.js');

module.exports = {
  data: {
    name: 'deletechan',
    description: 'Delete a channel created by the user.',
  },
  async execute(interaction) {
    try {
      // Check if the command is invoked in a guild (server)
      if (!interaction.guild) {
        await interaction.reply('This command can only be used in a server.');
        return;
      }

      // Get the channel name that was sent with the command
      const commandContent = interaction.content.slice('!deletechan'.length).trim();
      const channelNameToDelete = `${commandContent}`;

      // Find the channel with the specified name
      const channelToDelete = interaction.guild.channels.cache.find(
        (channel) => channel.name === channelNameToDelete && channel.type === 'GUILD_TEXT'
      );
console.log('test about topic= ',channelToDelete);
      // Check if the channel exists and if the author's name matches the creator's name
      if (
        channelToDelete &&
        channelToDelete.topic &&
        channelToDelete.topic.includes(`Created by: ${interaction.member.user.username}`)
      ) {
        await channelToDelete.delete();
        await interaction.reply('Channel deleted successfully.');
      } else {
        await interaction.reply("Either the channel doesn't exist or you don't have the permission to delete it.");
      }
    } catch (error) {
      console.error('Error deleting the channel:', error);
      await interaction.reply('An error occurred while deleting the channel.');
    }
  },
};
