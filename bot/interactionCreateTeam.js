const {ChannelType , ModalBuilder, TextInputBuilder, TextInputStyle, ActionRowBuilder,EmbedBuilder } = require('discord.js');
const axios = require('axios');

module.exports = {
  name: 'interactionCreateTeam',
  async execute(interaction) {
	  
    try {
      if (interaction.isStringSelectMenu() && interaction.customId === 'select_Team') {
        // Handle String Select Menu interactions
        const selectedRegion = interaction.values[0];
        const inputLabels = await fetchInputLabels();

        if (!inputLabels) {
          await interaction.reply('Failed to retrieve form labels from the server.');
          return;
        }

        const modal = new ModalBuilder()
          .setCustomId('levelingteam_modal')
          .setTitle('Provide Team Details');

        const input1 = new TextInputBuilder()
          .setCustomId('input1')
          .setLabel(inputLabels.input1 || 'Input Field 1')
          .setStyle(TextInputStyle.Short)
          .setRequired(true);

        const input2 = new TextInputBuilder()
          .setCustomId('input2')
          .setLabel(inputLabels.input2 || 'Input Field 2')
          .setStyle(TextInputStyle.Short)
          .setRequired(true);

        const input3 = new TextInputBuilder()
          .setCustomId('input3')
          .setLabel(inputLabels.input3 || 'Input Field 3')
          .setStyle(TextInputStyle.Short)
          .setRequired(true);

        const input4 = new TextInputBuilder()
          .setCustomId('input4')
          .setLabel(inputLabels.input4 || 'Input Field 4')
          .setStyle(TextInputStyle.Short)
          .setRequired(true);

        modal.addComponents(
          new ActionRowBuilder().addComponents(input1),
          new ActionRowBuilder().addComponents(input2),
          new ActionRowBuilder().addComponents(input3),
          new ActionRowBuilder().addComponents(input4),
        );

        await interaction.showModal(modal);

      } else if (interaction.isModalSubmit() && interaction.customId === 'levelingteam_modal') {
        // Handle Modal Submit interactions
        await interaction.deferReply(); // Acknowledge the interaction

        const input1Value = interaction.fields.getTextInputValue('input1');
        const input2Value = interaction.fields.getTextInputValue('input2');
        const input3Value = interaction.fields.getTextInputValue('input3');
        const input4Value = interaction.fields.getTextInputValue('input4');

        const inputLabels = await fetchInputLabels(); // Re-fetch the labels for dynamic titles

        try {
          const guild = interaction.guild;
          if (!guild) {
            console.error('Guild not found.');
            return;
          }

          // Create a public channel for leveling
          const channelName = `leveling-${input1Value.toLowerCase().replace(/ /g, '-')}`;
          const channel = await guild.channels.create({
            name: channelName,
            type: ChannelType.GuildText,
            topic: `Leveling details: ${input1Value}, ${input2Value}, ${input3Value}, ${input4Value}`,
            reason: 'Channel created for leveling information',
          });
          console.log(`Channel created: ${channelName}`);
		  
		   const embed = new EmbedBuilder()
              .setTitle('Leveling Details')
              .setURL('https://game3rade.com/')
              .setAuthor({ name: 'GodOfWar BOT', iconURL: 'https://i.imgur.com/YVbNLVA.jpeg', url: 'https://game3rade.com/' })
              .setDescription('Here are the details from the form:')
              .addFields(
                { name: `${inputLabels.input1 || 'Input 1'}`, value: input1Value, inline: true },
                { name: `${inputLabels.input2 || 'Input 2'}`, value: input2Value, inline: true },
                { name: `${inputLabels.input3 || 'Input 3'}`, value: input3Value, inline: true },
                { name: `${inputLabels.input4 || 'Input 4'}`, value: input4Value, inline: true }
              )
              .setThumbnail('https://i.imgur.com/YVbNLVA.jpeg')
              .setImage('https://i.imgur.com/YVbNLVA.jpeg') // Optional: Add a larger image if needed
              .setColor('#0099ff') // Customize the color as needed
              .setFooter({ text: 'Form Submission', iconURL: 'https://i.imgur.com/YVbNLVA.jpeg' });

            // Send the embed message to the private channel
            await channel.send({ embeds: [embed] });
 await channel.send({
  content: '@everyone React with your emoji to participate in the rolling!',
});
          // Send participation message and add reactions
          const participationMessage = await channel.send({
            content: 'React with your emoji to participate in the rolling!',
          });

          for (const emoji of ['🎉', '🥳', '🏆']) {
            await participationMessage.react(emoji);
          }

          const filter = (reaction, user) => {
            return ['🎉', '🥳', '🏆'].includes(reaction.emoji.name) && !user.bot;
          };

          const collector = participationMessage.createReactionCollector({ filter, time: 20000 }); // 20 seconds

          collector.on('end', async (collected) => {
            const participants = [];
            collected.forEach(reaction => {
              reaction.users.cache.forEach(user => {
                if (!user.bot) {
                  participants.push(user);
                }
              });
            });

            if (participants.length === 0) {
              await channel.send('No participants for the rolling.');
              return;
            }

            const winner = participants[Math.floor(Math.random() * participants.length)];
            const winnerId = winner.id;  // Get the winner's ID
            await channel.send(`Congratulations to the winner! Go to your private channel and communicate with the advertiser! 🎉`);

            const senderId = interaction.user.id;  // Get the sender's ID (interaction initiator)
            const senderUsername = interaction.user.username;
            const winnerUsername = winner.username || winner.user.username;

            // Create a private channel for the winner and the sender
            const privateChannelName = `private-${winnerUsername}-${senderUsername}`;
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
                  id: senderId, // Sender
                  allow: ['ViewChannel'],
                },
                {
                  id: winnerId, // Winner
                  allow: ['ViewChannel'],
                },
              ],
            });

            console.log(`Winner channel created: ${privateChannelName}`);

            // Create and send an embed with the form details
            const embed = new EmbedBuilder()
              .setTitle('Leveling Details')
              .setURL('https://game3rade.com/')
              .setAuthor({ name: 'GodOfWar BOT', iconURL: 'https://i.imgur.com/YVbNLVA.jpeg', url: 'https://game3rade.com/' })
              .setDescription('Here are the details from the form:')
              .addFields(
                { name: `${inputLabels.input1 || 'Input 1'}`, value: input1Value, inline: true },
                { name: `${inputLabels.input2 || 'Input 2'}`, value: input2Value, inline: true },
                { name: `${inputLabels.input3 || 'Input 3'}`, value: input3Value, inline: true },
                { name: `${inputLabels.input4 || 'Input 4'}`, value: input4Value, inline: true }
              )
              .setThumbnail('https://i.imgur.com/YVbNLVA.jpeg')
              .setImage('https://i.imgur.com/YVbNLVA.jpeg') // Optional: Add a larger image if needed
              .setColor('#0099ff') // Customize the color as needed
              .setFooter({ text: 'Form Submission', iconURL: 'https://i.imgur.com/YVbNLVA.jpeg' });

            // Send the embed message to the private channel
            await privateChannel.send({ embeds: [embed] });
await privateChannel.send({
  content: `Congratulations <@${winnerId}>! You are the winner! 🎉\n<@${senderId}>, you can now communicate with the winner in this private channel.`,
});
            await interaction.editReply(`Leveling channel created: ${channelName}`);

          });

        } catch (error) {
          console.error('Error creating channel:', error);
          await interaction.editReply('An error occurred while creating the channel.');
        }
      }
    } catch (error) {
      console.error('Error handling interaction:', error);
    }
  },
};

async function fetchInputLabels() {
  try {
    const response = await axios.get('http://152.228.154.27/team/input_labels_team.php');
    return response.data;
  } catch (error) {
    console.error('Error fetching input labels:', error);
    return null;
  }
}
