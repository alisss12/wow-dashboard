const { Client, ChannelType,EmbedBuilder,ActionRowBuilder,PermissionsBitField,roleMention, ButtonBuilder, ButtonStyle} = require('discord.js');
const axios = require('axios');

module.exports = {
  data: {
    name: 'addgold',
    description: 'Adds gold to the user\'s balance.',
  },
  async execute(message, args) {
    // Check if the message author has the necessary permission
          const dpsgetRole = message.guild.roles.cache.find((role) => role.name === 'Advertiser');
        if (!dpsgetRole || !message.member.roles.cache.has(dpsgetRole.id)) {
            await message.reply('You need to have the "Advertiser" role to use this command.');
            return;
        }
 const commandContent = message.content.slice('!addgold'.length).trim();
  const commandArgs = commandContent.split(' ');

 
  const targetUser = message.author.id;
 const amountRegex = /\d+$/;
  
    const amountMatch = message.content.match(amountRegex);
    const amount = amountMatch ? parseInt(amountMatch[0], 10) : null;

  // Ensure a valid user ID and a valid positive amount are specified
  if (!targetUser &&isNaN(amount)&& amount <= 0) {
    return message.reply('Usage: !addgold <amount>');
  }

  // Prepare data for POST request to PHP server
  const data = {
    userId: targetUser,
    amount: amount,
  };

  try {
    // Make the POST request to the PHP server and handle the result
    const response = await axios.post('http://152.228.154.27/commandexe/add_gold.php', data);

    // Handle the response from the PHP server
   	if (response.data.status === 'success') {
      const updatedBalanceMessage = `Successfully added ${amount} gold to <@${targetUser}>'s balance.`;
      await message.channel.send(updatedBalanceMessage);
	  	const link = response.data.link;
		const transactionId = response.data.transactionId;
	  	const user = message.author;
  { await user.send(`\`\`\`
      Your transaction ID is: ${transactionId}
      Your code is: ${link}
      Total amount for this payment is: ${amount}
      
    \`\`\``);
    }} else {
       return message.reply(`Error updating balances: ${response.data.message}`);
    }
  } catch (error) {
    console.error('An error occurred while adding gold:', error);
    return message.reply('An error occurred while adding gold. Please try again later.');
  }
}
}