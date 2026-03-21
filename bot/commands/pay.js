const Discord = require('discord.js');
const { PermissionsBitField } = Discord;
const axios = require('axios');

module.exports = {
  data: {
    name: 'pay',
    description: 'Pays an amount to a maximum of 4 users.',
  },
  async execute(message) {
    // Check if the message author has the necessary permission
     const dpsgetRole = message.guild.roles.cache.find((role) => role.name === 'Advertiser');
        if (!dpsgetRole || !message.member.roles.cache.has(dpsgetRole.id)) {
            await message.reply('You need to have the "Advertiser" role to use this command.');
            return;
        }

    // Parse the message to find users and amount
    const amountRegex = /\d+$/;
    const mentions = message.mentions.users;
    const amountMatch = message.content.match(amountRegex);
    const amount = amountMatch ? parseInt(amountMatch[0], 10) : null;

    // Check the maximum number of mentions
    if (mentions.size > 4) {
      return message.reply('You can only pay up to 4 users at a time.');
    }

    // Ensure users are mentioned and amount is specified
    if (mentions.size === 0 && amount === null) {
      return message.reply('You need to mention users and specify an amount to pay.');
    }

    // Ensure the amount is positive
    if (amount <= 0) {
      return message.reply('The amount to be paid should be more than zero.');
    }

    // Prepare data for POST request
    const channelID = message.channel.id;
    const commandUserID = message.author.id;
    const mentionedUserIDs = mentions.map(user => user.id);

    try {
      // Make the POST request and handle the result
      const response = await axios.post('http://152.228.154.27/wowbot/commandexe/paymentcheck.php', {
        channelId: channelID,
        commandUserId: commandUserID,
        userIDs: mentionedUserIDs,
        amount: amount
      });

		if (response.data.status === 'success') {
		const paidUsers = mentions.map(user => `${user.username} (${amount})`).join(', ');
		const paymentMessage = `Successfully paid ${amount} to ${paidUsers} spilted.`;

			// Send the payment message to the channel
		await message.channel.send(paymentMessage);

			// Send a private message to the command user with the transaction details
		const link = response.data.link;
		const transactionId = response.data.transactionId;
		const totalBalance = response.data.totalBalance;
		const remainingCredit = response.data.remainingCredit;
		const user = message.author;

  try { await user.send(`\`\`\`
      Your transaction ID is: ${transactionId}
      Your code is: ${link}
      Total amount for this payment is: ${amount}
      Your total balance is: ${totalBalance}
      Remaining credit is: ${remainingCredit}
    \`\`\``);
	
	 const userDetails = response.data.userDetails.slice(0, 4); // Limit to a maximum of 4 users
  for (const userDetail of userDetails) {
    const user = await message.client.users.fetch(userDetail.userID);
    await user.send(`Hello ${user.username}! Your split amount is: ${userDetail.splitAmount}, and your balance is: ${userDetail.balance}. This message is from ${message.author.username}.`);
  }
	} catch (error) {
          console.error('Unable to send a private message:', error);
          // Handle errors if the bot is unable to send a private message
          // You can choose to notify the user or handle it differently
        }
      } else {
        // Handle the case where the payment wasn't successful
        return message.reply(`Error updating balances: ${response.data.message}`);
      }
    } catch (error) {
      console.error('An error occurred during the payment processing:', error);
      return message.reply('An error occurred while processing payments.');
    }
  },
};
