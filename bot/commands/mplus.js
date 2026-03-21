const { StringSelectMenuBuilder, ActionRowBuilder, ButtonBuilder, ButtonStyle } = require('discord.js');

module.exports = {
  data: {
    name: 'mplus',
    description: 'Command to open a form with options for team EU or US.',
  },
  async execute(interaction) {
    try {
      if (!interaction.guild) {
        return await interaction.reply({ content: 'This command can only be used in a server.', ephemeral: true });
      }

      const selectMenu = new StringSelectMenuBuilder()
        .setCustomId('select_Team')
        .setPlaceholder('Select Team Region')
        .addOptions([
          { label: 'Team EU', value: 'team_eu' },
          { label: 'Team US', value: 'team_us' },
        ]);

      const cancelButton = new ButtonBuilder()
        .setCustomId('cancel_team_select')
        .setLabel('Cancel')
        .setStyle(ButtonStyle.Secondary);

      const row1 = new ActionRowBuilder().addComponents(selectMenu);
      const row2 = new ActionRowBuilder().addComponents(cancelButton);

      await interaction.reply({
        content: 'Please choose a Team region:',
        components: [row1, row2],
        ephemeral: true,
      });
    } catch (error) {
      console.error('Error executing mplus command:', error);
      await interaction.reply({ content: 'An error occurred.', ephemeral: true }).catch(() => {});
    }
  },
};