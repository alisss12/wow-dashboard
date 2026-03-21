const { Client, ChannelType } = require('discord.js');


module.exports = {
  data: {
    name: 'channellist',
    description: 'List all text channels in the server.',
  },
  async execute(interaction) {
    try {
      // Check if the command is invoked in a guild (server)
      if (!interaction.guild) {
        await interaction.reply('This command can only be used in a server.');
        return;
      }

      // Fetch all channels in the guild
      const channels = interaction.guild.channels.cache;

      // Filter out only the text channels
      const textChannels = channels.filter(channel => channel.type === ChannelType.GuildText);

      // Create a list of channel names
      const channelList = textChannels.map(channel => `- ${channel.name}`).join('\n');

      // Send the list as a reply
      await interaction.reply(`List of text channels in this server:\n${channelList}`);
    } catch (error) {
      console.error('Error listing channels:', error);
      await interaction.reply('An error occurred while listing the channels.');
    }
  },
};

