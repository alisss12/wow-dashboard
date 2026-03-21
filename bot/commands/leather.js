const { Client, ChannelType,EmbedBuilder,ActionRowBuilder,PermissionsBitField,roleMention, ButtonBuilder, ButtonStyle} = require('discord.js');
const axios = require('axios');
module.exports = {
  data: {
    name: 'leather',
    description: 'plate  command to create a new channel.',
  },
  async execute(interaction) {
    try {
      // Check if the command is invoked in a guild (server)
      if (!interaction.guild) {
        await interaction.reply('This command can only be used in a server.');
        return;
      }
	    if (interaction.channel.name !== 'team-boost') {
        await interaction.reply('This command can only be used in a channel named "team-boost".');
        return;
      }
	  
	      const dpsgetRole = interaction.guild.roles.cache.find((role) => role.name === 'Advertiser');
        if (!dpsgetRole || !interaction.member.roles.cache.has(dpsgetRole.id)) {
            await interaction.reply('You need to have the "Advertiser" role to use this command.');
            return;
        }
		// Find roles for tank and leather

	
const leatherRole = interaction.guild.roles.cache.find((role) => role.name === 'leather');
  // Get the command content
      const commandContent = interaction.content.slice('!leather'.length).trim();

      // Split the command content into different parts
      const [ type, gold, hint] = commandContent.split(' ');

      // Get the username of the author who used the command
      const authorUsername = interaction.member.user.username;

  const goldValue = parseInt(gold, 10) * 1000;
const calculatedGoldValue = ((goldValue - (goldValue * 0.04)) - (goldValue * 0.35)) / 4;
const formattedGoldValue = goldValue.toLocaleString();
const formattedShearedGoldValue = calculatedGoldValue.toLocaleString();
// Create the channel name
const channelName = `leather-${type}-${gold}-${authorUsername}`;
  // Check if either type or gold is undefined
      if (!type || !gold) {
        interaction.reply({ content: 'Please provide both type and gold values.', ephemeral: true });
        return;
      }
      // Create a new text channel
      const newChannel = await interaction.guild.channels.create({
        name: channelName,
        type: ChannelType.GuildText,
		
		//set ascces role
		permissionOverwrites: [
  {
    id: interaction.member.id,
 allow: [PermissionsBitField.Flags.ManageChannels],
  },
       {
            id: interaction.guild.roles.everyone, // Deny access for @everyone
            deny: [PermissionsBitField.Flags.ViewChannel], // Deny viewing the channel for @everyone
          },
  {
    id: dpsgetRole.id,  
 allow: [PermissionsBitField.Flags.ViewChannel],
  },
 
    // Allow leather role to view the channel
    {
      id: leatherRole.id,
      allow: [PermissionsBitField.Flags.ViewChannel],
    },
],
      });

      // Log the ID of the newly created channel
      console.log('New channel ID:', newChannel.id);
	  
	     // Mention the leather and tank role in the message content


// Convert role mentions to string

const leatherRoleMention = leatherRole?.toString();
    console.log('Bot mention all leather:', leatherRoleMention);

    //  await interaction.reply('New channel created successfully!');
	  const confirm = new ButtonBuilder()
			.setCustomId('confirm')
			.setLabel('test red')
			.setStyle(ButtonStyle.Danger);

		const cancel = new ButtonBuilder()
			.setCustomId('cancel')
			.setLabel('test gray')
			.setStyle(ButtonStyle.Secondary);

	const Primary = new ButtonBuilder()
			.setCustomId('Primary')
			.setLabel('test blue')
			.setStyle(ButtonStyle.Primary);
				const Success = new ButtonBuilder()
			.setCustomId('Success')
			.setLabel('test green')
			.setStyle(ButtonStyle.Success);
				const link =  new ButtonBuilder()
	.setLabel('test link')
	.setURL('https://game3rade.com/')
	.setStyle(ButtonStyle.Link);
			
		const row = new ActionRowBuilder()
			.addComponents(cancel, confirm,Primary,Success,link);
	  
	
      // Send a message to the newly created channel
	     const embed = new EmbedBuilder()
        .setTitle(`Channel Created By ${authorUsername}`)
			.setURL('https://game3rade.com/')
			.setAuthor({ name: 'GodOfWar BOT', iconURL: 'https://i.imgur.com/YVbNLVA.jpeg', url: 'https://game3rade.com/' })
        .setDescription(`A new channel has been created with the command ${interaction.content}`)
			.setThumbnail('https://i.imgur.com/YVbNLVA.jpeg')
.addFields(
		{ name: 'Regular field title', value: 'Some value here' },
		{ name: '\u200B', value: '\u200B' },
		{ name: 'TYPE :', value: `${type}`, inline: true },
		{ name: 'Advertiser(%35)', value: `<@${interaction.member.user.id}>`, inline: true },
		{ name: '\u200B', value: '\u200B' ,inline: false,},
				{ name: 'Order(Total Gold) :', value: `${formattedGoldValue} Gold`, inline: true },
		{ name: 'Share per booster', value: `${formattedShearedGoldValue} Gold`, inline: true },
	)
	.setImage('https://i.imgur.com/YVbNLVA.jpeg')
		.setFooter({ text: 'Some footer text here', iconURL: 'https://i.imgur.com/YVbNLVA.jpeg' });

      await newChannel.send({
  // content: `${dpsRoleMention} A new channel has been created!`,
        embeds: [embed],

	//		components: [row],
      });


    await newChannel.send({
     // Replace this line with the updated content
content: `${leatherRoleMention} A new channel has been created!`,

     //   embeds: [embed],
 
	//		components: [row],
      });
	  //post data
	  
	  const postDatatt = {
  channelName: channelName,
   channelID: newChannel.id, // Include the channel ID here
  type: type,
  gold: gold,
  authorUsername: authorUsername,
  // Add more relevant data here
  embed: embed,
  leatherRoleMention: leatherRoleMention,
 
};
// Assuming leatherRole and tankRole are already defined Role objects from your guild
const leatherMention = leatherRole.toString(); // Converts the leather role to a mention string




// Prepare the postData object with the transaction message
const postData = {
    transactionType: type,
	channelID: newChannel.id,
    goldAmount: gold,
    userId: interaction.member.user.id,
    leatherRoleMention: leatherRole.id,

};


// Send the POST request to the PHP server
axios.post('http://152.228.154.27/commandexe/channelmake.php', postData)
    .then(response => {
        console.log('Data posted successfully:', response.data);
    })
    .catch(error => {
        console.error('Failed to send data:', error);
    });
// Send data to the specified endpoint
await fetch('http://152.228.154.27/commandexe/checkdata.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify(postDatatt),
});
   // Function to handle button interactions
      const handleButtonInteraction = async (interaction) => {
        if (!interaction.isButton()) return;

        // Check if the correct button was clicked
        if (interaction.customId === 'Success') {
      await interaction.deferReply();
		await wait(4000);
		await interaction.Reply('click!!');
        }
      };

      // Wait for button interactions
      interaction.client.on('interactionCreate', handleButtonInteraction);
 // Change 5000 to the desired delay in milliseconds (optional)

    } catch (error) {
      console.error('Error creating a new channel:', error);
      await interaction.reply('An error occurred while creating the channel.');
    }
  },
};

