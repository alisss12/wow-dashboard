const {
  ChannelType,
  ModalBuilder,
  TextInputBuilder,
  TextInputStyle,
  ActionRowBuilder,
  EmbedBuilder,
  ButtonBuilder,
  ButtonStyle,
} = require('discord.js');
const mysql = require('mysql2/promise');
const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '..', '.env') });

// MySQL Pool
const pool = mysql.createPool({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
  connectionLimit: process.env.DB_CONN_LIMIT || 10,
  waitForConnections: true,
  queueLimit: 0
});

// Fixed labels
async function getInputLabelsTeam() {
  return {
    input1: 'M+',
    input2: 'Armor Stack',
    input3: 'Key',
    input4: 'Cut',
    input5: 'Additional Notes'
  };
}

// Temporary storage for selected region between select menu and modal
const interactionContext = new Map();

module.exports = {
  name: 'interactionCreateteam',
  async execute(interaction) {
    try {
      const userId = interaction.user.id;

      // Cancel button from initial selection
      if (interaction.isButton() && interaction.customId === 'cancel_team_select') {
        await interaction.update({ content: 'M+ order canceled.', components: [] });
        return;
      }

      // String Select Menu - Region selection (USA / EU)
      if (interaction.isStringSelectMenu() && interaction.customId === 'select_Team') {
        const selectedValue = interaction.values[0];
        interactionContext.set(userId, { selectedValue });

        const inputLabels = await getInputLabelsTeam();

        const modal = new ModalBuilder()
          .setCustomId('team_modal')
          .setTitle(`${selectedValue === 'team_us' ? 'USA' : 'EU'} M+ Boost Details`);

        ['input1', 'input2', 'input3', 'input4', 'input5'].forEach((input) => {
          modal.addComponents(
            new ActionRowBuilder().addComponents(
              new TextInputBuilder()
                .setCustomId(input)
                .setLabel(inputLabels[input])
                .setStyle(TextInputStyle.Short)
                .setRequired(true)
                .setMaxLength(100)
            )
          );
        });

        // Critical fix: wrap showModal in try/catch to prevent crashes on network issues
        try {
          await interaction.showModal(modal);
        } catch (error) {
          console.error('Failed to show modal:', error);
          if (!interaction.replied && !interaction.deferred) {
            await interaction.reply({
              content: 'Failed to open the form. Please try again or check your connection.',
              ephemeral: true
            }).catch(() => {});
          }
        }
        return;
      }

      // Modal Submit
      if (interaction.isModalSubmit() && interaction.customId === 'team_modal') {
        await interaction.deferReply({ ephemeral: true });

        const context = interactionContext.get(userId);
        if (!context || !context.selectedValue) {
          await interaction.editReply({ content: 'Session expired. Please start again.' });
          return;
        }
        const selectedValue = context.selectedValue;

        const inputValues = {
          input1: interaction.fields.getTextInputValue('input1'),
          input2: interaction.fields.getTextInputValue('input2'),
          input3: interaction.fields.getTextInputValue('input3'),
          input4: interaction.fields.getTextInputValue('input4'),
          input5: interaction.fields.getTextInputValue('input5')
        };

        const inputLabels = await getInputLabelsTeam();

        const guild = interaction.guild;
        if (!guild) {
          await interaction.editReply({ content: 'Guild not found.' });
          return;
        }

        const channelName = selectedValue === 'team_us' ? 'team-boost-usa' : 'team-boost-eu';
        const teamBoostChannel = guild.channels.cache.find(ch => ch.name === channelName);
        if (!teamBoostChannel) {
          await interaction.editReply({ content: `Channel "${channelName}" not found.` });
          return;
        }

        const cancelButton = new ButtonBuilder()
          .setCustomId('cancel_order_team')
          .setLabel('Cancel Order')
          .setStyle(ButtonStyle.Danger);

        const embed = new EmbedBuilder()
          .setTitle('New M+ Boost Order')
          .setDescription('React with 🎉 🥳 or 🏆 to join!')
          .addFields(
            { name: inputLabels.input1, value: inputValues.input1, inline: true },
            { name: inputLabels.input2, value: inputValues.input2, inline: true },
            { name: inputLabels.input3, value: inputValues.input3, inline: true },
            { name: inputLabels.input4, value: inputValues.input4, inline: true },
            { name: inputLabels.input5, value: inputValues.input5, inline: false },
            { name: 'Region', value: selectedValue === 'team_us' ? 'USA' : 'EU', inline: false },
            { name: 'Ordered By', value: `<@${userId}>`, inline: false }
          )
          .setThumbnail('https://i.imgur.com/YVbNLVA.jpeg')
          .setImage('https://i.imgur.com/YVbNLVA.jpeg')
          .setColor('#0099ff');

        const participationMessage = await teamBoostChannel.send({
          content: '@everyone New M+ order! React to participate!',
          embeds: [embed],
          components: [new ActionRowBuilder().addComponents(cancelButton)]
        });

        // Add reactions
        for (const emoji of ['🎉', '🥳', '🏆']) {
          await participationMessage.react(emoji);
        }

        await interaction.editReply({ content: 'Order posted successfully!' });

        let winner = null;

        const filter = (reaction, user) => ['🎉', '🥳', '🏆'].includes(reaction.emoji.name) && !user.bot;

        // First phase: random winner if reactions within 50 seconds
        const randomCollector = participationMessage.createReactionCollector({ filter, time: 50000 });

        randomCollector.on('end', async (collected) => {
          if (winner) return;

          const participants = [];
          collected.forEach(reaction => {
            reaction.users.cache.forEach(user => {
              if (!user.bot && !participants.some(p => p.id === user.id)) {
                participants.push(user);
              }
            });
          });

          if (participants.length > 0) {
            winner = participants[Math.floor(Math.random() * participants.length)];
            await finalizeOrder(winner);
            return;
          }

          // Second phase: first-come-first-served (5 minutes)
          const firstCollector = participationMessage.createReactionCollector({ filter, time: 300000 });

          firstCollector.on('collect', (_, user) => {
            if (!winner) {
              winner = user;
              firstCollector.stop();
            }
          });

          firstCollector.on('end', async () => {
            if (winner) {
              await finalizeOrder(winner);
            } else {
              await participationMessage.edit({
                content: 'No one reacted — order canceled.',
                embeds: [],
                components: []
              });
            }
          });
        });

        // Cancel button collector
        const cancelFilter = (i) => i.customId === 'cancel_order_team' && i.user.id === userId;
        const cancelCollector = participationMessage.createMessageComponentCollector({ filter: cancelFilter });

        cancelCollector.on('collect', async (i) => {
          randomCollector.stop();
          await participationMessage.edit({
            content: 'Order canceled by creator.',
            embeds: [],
            components: []
          });
          await i.reply({ content: 'Order canceled.', ephemeral: true });
        });

        // Finalize order and create private channel
        async function finalizeOrder(booster) {
          const uniqueId = Math.random().toString(36).substring(2, 10);
          const channelName = `mplus-${uniqueId}-${booster.username}-${interaction.user.username}`
            .toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .slice(0, 100);

          const privateChannel = await guild.channels.create({
            name: channelName,
            type: ChannelType.GuildText,
            permissionOverwrites: [
              { id: guild.id, deny: ['ViewChannel'] },
              { id: interaction.user.id, allow: ['ViewChannel', 'SendMessages', 'ReadMessageHistory'] },
              { id: booster.id, allow: ['ViewChannel', 'SendMessages', 'ReadMessageHistory'] }
            ]
          });

          const orderId = Date.now().toString(36) + Math.random().toString(36).substr(2, 5);

          try {
            await pool.execute(
              `INSERT INTO mplus_orders
              (order_id, customer_id, booster_id, region, mplus_level, armor_stack, mplus_key, cut, notes, channel_id)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
              [
                orderId,
                interaction.user.id,
                booster.id,
                selectedValue === 'team_us' ? 'USA' : 'EU',
                inputValues.input1,
                inputValues.input2,
                inputValues.input3,
                inputValues.input4,
                inputValues.input5,
                privateChannel.id
              ]
            );
            console.log(`M+ order saved: ${orderId} | Channel: ${privateChannel.name} (${privateChannel.id})`);
          } catch (dbError) {
            console.error('DB save failed:', dbError);
          }

          const resultEmbed = new EmbedBuilder()
            .setTitle('M+ Boost Assigned!')
            .setColor('#00ff00')
            .addFields(
              { name: 'Region', value: selectedValue === 'team_us' ? 'USA' : 'EU', inline: true },
              { name: inputLabels.input1, value: inputValues.input1, inline: true },
              { name: inputLabels.input2, value: inputValues.input2, inline: true },
              { name: inputLabels.input3, value: inputValues.input3, inline: true },
              { name: inputLabels.input4, value: inputValues.input4, inline: true },
              { name: inputLabels.input5, value: inputValues.input5, inline: false },
              { name: 'Customer', value: `<@${interaction.user.id}>`, inline: false },
              { name: 'Booster', value: `<@${booster.id}>`, inline: false }
            )
            .setTimestamp();

          await privateChannel.send({
            content: `Congratulations <@${booster.id}>! You have been selected!`,
            embeds: [resultEmbed]
          });

          await teamBoostChannel.send(`🎉 <@${booster.id}> has been assigned! Check your private channel.`);

          await participationMessage.edit({
            content: `Order completed! Booster: <@${booster.id}> | Channel: ${privateChannel}`,
            embeds: [],
            components: []
          });
        }

        // Clean up context
        interactionContext.delete(userId);
      }
    } catch (error) {
      console.error('Error in interactionCreateteam:', error);
      if (!interaction.replied && !interaction.deferred) {
        await interaction.reply({ content: 'An unexpected error occurred.', ephemeral: true }).catch(() => {});
      }
    }
  },
};