// ... (other imports)
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
const axios = require('axios');

// Object to manage interaction states
const interactionContext = new Map();

module.exports = {
  name: 'interactionCreate',
  async execute(interaction) {
    try {
      const userId = interaction.user.id;

      // Add these variables to manage timers and collectors
      let lastChanceTimeout;
      let collectorButton;
      let cancelOrderCollector;

      // Handle String Select Menu Interaction
      if (interaction.isStringSelectMenu() && interaction.customId === 'select_leveling') {
        if (interactionContext.has(userId)) {
          await interaction.reply({ content: 'You already have an ongoing request. Please finish it first.', ephemeral: true });
          return;
        }

        const selectedValue = interaction.values[0];
        console.log('Selected Value:', selectedValue);
        interactionContext.set(userId, { selectedValue });

        const inputLabels = await fetchInputLabelsTeam();
        if (!inputLabels) {
          await interaction.reply('Failed to retrieve form labels from the server.');
          return;
        }

        const modal = new ModalBuilder()
          .setCustomId('leveling_modal')
          .setTitle(`${selectedValue} - Provide Leveling Details`);

        const inputs = ['input1', 'input2', 'input3', 'input4'];
        inputs.forEach((input, index) => {
          const textInput = new TextInputBuilder()
            .setCustomId(input)
            .setLabel(inputLabels[input] || `Input Field ${index + 1}`)
            .setStyle(TextInputStyle.Short)
            .setRequired(true);
          modal.addComponents(new ActionRowBuilder().addComponents(textInput));
        });

        await interaction.showModal(modal);

      } else if (interaction.isModalSubmit() && interaction.customId === 'leveling_modal') {
        await interaction.deferReply(); // Acknowledge the interaction

        const input1Value = interaction.fields.getTextInputValue('input1');
        const input2Value = interaction.fields.getTextInputValue('input2');
        const input3Value = interaction.fields.getTextInputValue('input3');
        const input4Value = interaction.fields.getTextInputValue('input4');


        console.log('Input Values:', { input1Value, input2Value, input3Value, input4Value });

        const selectedValue = interactionContext.get(userId)?.selectedValue || 'No value selected';
        const inputLabels = await fetchInputLabelsTeam();

        try {
          const guild = interaction.guild;
          if (!guild) {
            console.error('Guild not found.');
            return;
          }

          let teamBoostChannelName = '';
          if (selectedValue === 'leveling_eu') {
            teamBoostChannelName = 'lvl-up';
          } else if (selectedValue === 'leveling_us') {
            teamBoostChannelName = 'lvl-up';
          } else {
            console.error('Invalid team selection.');
            await interaction.reply({ content: 'Error: Invalid team selection.', ephemeral: true });
            return;
          }

          const teamBoostChannel = guild.channels.cache.find(channel => channel.name === teamBoostChannelName);

          if (!teamBoostChannel) {
            console.error('Team boost channel not found.');
            await interaction.reply({ content: 'Error: Team boost channel not found.', ephemeral: true });
            return;
          }

          const cancelButton = new ButtonBuilder()
            .setCustomId('cancel_order_team')
            .setLabel('Cancel Order')
            .setStyle(ButtonStyle.Danger);

          const lastChanceButton = new ButtonBuilder()
            .setCustomId('last_chance_button_team')
            .setLabel('Last Chance')
            .setStyle(ButtonStyle.Primary)
            .setDisabled(true); // Initially disabled

          const embed = new EmbedBuilder()
            .setTitle('Leveling Details')
            .setDescription('Here are the details from the form:')
            .addFields(
              { name: `${inputLabels.input1 || 'Input 1'}`, value: input1Value, inline: true },
              { name: `${inputLabels.input2 || 'Input 2'}`, value: input2Value, inline: true },
              { name: `${inputLabels.input3 || 'Input 3'}`, value: input3Value, inline: true },
              { name: `${inputLabels.input4 || 'Input 4'}`, value: input4Value, inline: true }
            )
            .setThumbnail('https://i.imgur.com/YVbNLVA.jpeg')
            .setImage('https://i.imgur.com/YVbNLVA.jpeg')
            .setColor('#0099ff');

          let participationMessage = await teamBoostChannel.send({
            embeds: [embed],
            content: '@everyone React with your emoji to participate in the rolling!',
            components: [new ActionRowBuilder().addComponents(cancelButton, lastChanceButton)]
          });

          // Add reactions
          for (const emoji of ['🎉', '🥳', '🏆']) {
            await participationMessage.react(emoji);
          }

          await interaction.editReply({ content: 'Form submitted successfully!', ephemeral: true });

          const filter = (reaction, user) => ['🎉', '🥳', '🏆'].includes(reaction.emoji.name) && !user.bot;
          const collector = participationMessage.createReactionCollector({ filter, time: 15000 });

          collector.on('end', async (collected) => {
            const participants = [];
            collected.forEach(reaction => {
              reaction.users.cache.forEach(user => {
                if (!user.bot) {
                  participants.push(user);
                }
              });
            });

            let winner;
            if (participants.length > 0) {
              winner = participants[Math.floor(Math.random() * participants.length)];
            }

    if (participationMessage) {
  try {
   
    // Attempt to edit the message
    await participationMessage.edit({
      components: [
        new ActionRowBuilder().addComponents(cancelButton, lastChanceButton.setDisabled(false))
      ]
    }


	);
  } catch (error) {
    // Handle the error if the message no longer exists
    if (error.code === 10008) { // Check for "Unknown Message" error
      console.warn('Participation message has been deleted or does not exist.');
      participationMessage = null; // Clear the reference to avoid further attempts to edit
    } else {
      console.error('Error editing participation message:', error);
    }
  }
}



            if (!winner) {
        lastChanceTimeout = setTimeout(async () => {
  if (participationMessage) {
    try {
      // Try to edit the participation message if it still exists
      await participationMessage.edit({
        content: 'Order canceled due to inactivity.',
        embeds: [],
        components: [],
      });
    } catch (error) {
      // Handle the case where the message no longer exists (DiscordAPIError[10008])
      if (error.code === 10008) {
        console.error('Error: Participation message no longer exists (likely deleted).');
      } else {
        // Log any other errors
        console.error('Error editing participation message after timeout:', error);
      }
    }
  } else {
    console.log('Participation message is null or has been deleted; skipping edit after timeout.');
  }
}, 300000); // Duration for Last Chance inactivity


              const filterButton = (i) => i.customId === 'last_chance_button_team' && !i.user.bot;
            if (participationMessage) {
  const collectorButton = participationMessage.createMessageComponentCollector({
    filter: filterButton,
    time: 60000
			});

              collectorButton.on('collect', async (i) => {
                clearTimeout(lastChanceTimeout); // Clear the timeout if button clicked
                winner = i.user;
                await i.update({
                  content: `A winner has been selected`,
                  components: [],
                });

                await participationMessage.edit({
                  content: 'Order taken! Congratulations to the winner: ',
                  embeds: [],
                  components: []
                });

                await createPrivateChannel(
                  guild,
                  interaction.user,
                  winner,
                  selectedValue,
                  input1Value,
                  input2Value,
                  input3Value,
                  input4Value
                  
                );
              });
			}
            } else {
              await teamBoostChannel.send(`Congratulations to the winner`);
              await participationMessage.edit({
                content: 'Order taken! Congratulations to the winner: ',
                embeds: [],
                components: []
              });

              await createPrivateChannel(guild, interaction.user, winner, selectedValue, input1Value, input2Value, input3Value, input4Value);
            }
          });

          // Cancel Order button collector
     const cancelOrderFilter = (i) => i.customId === 'cancel_order_team' && !i.user.bot;
cancelOrderCollector = participationMessage.createMessageComponentCollector({ filter: cancelOrderFilter, time: 60000 });

cancelOrderCollector.on('collect', async (i) => {
  try {
    if (participationMessage) {
      await participationMessage.delete(); // Delete the participation message
      participationMessage = null; // Clear reference
    }
    clearTimeout(lastChanceTimeout); // Clear the timeout
    if (collectorButton) {
      collectorButton.stop(); // Stop the button collector
    }
    await i.reply({ content: 'Order has been canceled.', ephemeral: true }); // Notify the user
  } catch (error) {
    console.error('Error deleting participation message:', error);
    await i.reply({ content: 'Failed to cancel the order. The message may have already been deleted.', ephemeral: true });
  }
});

// Inside the reaction collector
collector.on('end', async (collected) => {
  // (Your existing logic)
  
  if (participationMessage) {
    try {
      // Attempt to edit the message
      await participationMessage.edit({
        components: [
          new ActionRowBuilder().addComponents(cancelButton, lastChanceButton.setDisabled(false))
        ]
      });
    } catch (error) {
      // Check if the error is due to the message no longer existing (DiscordAPIError[10008])
      if (error.code === 10008) {
        console.error('Error: Participation message no longer exists.');
      } else {
        // Log other errors
        console.error('Error editing participation message:', error);
      }
    }
  } else {
    console.log('Participation message is null or has been deleted; skipping edit.');
  }
});


        } catch (error) {
          console.error('Error handling modal submission:', error);
          await interaction.editReply('There was an error while handling your submission. Please try again.');
        }

        interactionContext.delete(userId);
      }
    } catch (error) {
      console.error('Error handling interaction:', error);
      await interaction.reply('An unexpected error occurred. Please try again.');
    }
  },
};

// Helper function to fetch input labels from an external source
async function fetchInputLabelsTeam() {
  try {
   const response = await axios.get('http://152.228.154.27/wowbot/commandexe/team/input_labels.php');
    return response.data;
  } catch (error) {
    console.error('Error fetching input labels:', error);
    return null;
  }
}

// Helper function to create a private channel for the winner
async function createPrivateChannel(guild, owner, winner, selectedValue, input1Value, input2Value, input3Value, input4Value) {
  try {
    const inputLabels = await fetchInputLabelsTeam(); // Re-fetch the labels for dynamic titles
    const privateChannelName = `private-${winner.username}-${owner.username}`;
    const privateChannel = await guild.channels.create({
      name: privateChannelName,
      type: ChannelType.GuildText,
      topic: 'Private channel for winner and advertiser',
      reason: 'Channel created for private communication between winner and sender',
      permissionOverwrites: [
        {
          id: guild.id, // @everyone role
          deny: ['ViewChannel'],
        },
        {
          id: owner.id, // Sender (Owner)
          allow: ['ViewChannel'],
        },
        {
          id: winner.id, // Winner
          allow: ['ViewChannel'],
        },
      ],
    });

    console.log(`Winner channel created: ${privateChannelName}`);

    const embed = new EmbedBuilder()
      .setTitle('Winner Details')
      .setDescription('Here are the details from the form:')
      .addFields(
        { name: 'Type', value: selectedValue, inline: true },
        { name: `${inputLabels.input1 || 'Input 1'}`, value: input1Value, inline: true },
        { name: `${inputLabels.input2 || 'Input 2'}`, value: input2Value, inline: true },
        { name: `${inputLabels.input3 || 'Input 3'}`, value: input3Value, inline: true },
        { name: `${inputLabels.input4 || 'Input 4'}`, value: input4Value, inline: true }
      )
      .addFields(
        { name: 'Selected By', value: `<@${owner.id}>`, inline: false },
        { name: 'Winner', value: `<@${winner.id}>`, inline: false }
      )
      .setThumbnail('https://i.imgur.com/YVbNLVA.jpeg')
      .setColor('#0099ff');

    await privateChannel.send({ content: `Congratulations ${winner.tag}!`, embeds: [embed] });
  } catch (error) {
    console.error('Error creating private channel:', error);
  }
}