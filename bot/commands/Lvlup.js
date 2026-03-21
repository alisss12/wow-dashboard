const { StringSelectMenuBuilder, ActionRowBuilder } = require('discord.js');

module.exports = {
  data: {
    name: 'lvlup',
    description: 'Command to open a form with options for leveling EU or US.',
  },
  async execute(interaction) {
    try {
      if (!interaction.guild) {
        await interaction.reply('This command can only be used in a server.');
        return;
      }

      const selectMenu = new StringSelectMenuBuilder()
        .setCustomId('select_leveling')
        .setPlaceholder('Select Leveling Region')
        .addOptions([
          {
            label: 'Leveling EU',
            value: 'leveling_eu',
          },
          {
            label: 'Leveling US',
            value: 'leveling_us',
          },
        ]);

      const row = new ActionRowBuilder()
        .addComponents(selectMenu);

      await interaction.reply({
        content: 'Please choose a leveling region:',
        components: [row],
        ephemeral: true,
      });

    } catch (error) {
      console.error('Error executing the form command:', error);
      await interaction.reply('An error occurred while executing the form command.');
    }
  },
};
