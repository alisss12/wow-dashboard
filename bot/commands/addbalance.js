const { PermissionsBitField } = require('discord.js');

module.exports = {
  data: {
    name: 'addbalance',
    description: 'Pays an amount to multiple users.',
  },
  async execute(message) {
    // Check if the message author has the necessary permission
    if (!message.member.permissions.has(PermissionsBitField.Flags.ManageRoles)) {
      return message.reply('You do not have the required permissions to make payments.');
    }

    // Parse the message to find users and amount
    const amountRegex = /\d+$/; // regex to match the amount at the end of the command
    const mentions = message.mentions.users; // get mentioned users
    const amount = message.content.match(amountRegex) ? parseInt(message.content.match(amountRegex)[0], 10) : null;

    if (!amount &&( mentions.size === 0)) {
      return message.reply('You need to mention users and specify an amount to pay.');
    }

    if (amount <= 0) {
      return message.reply('The amount to be paid should be more than zero.');
    }

    // Here you would typically interface with your payment processor/database
    // For simulation, we simply reply with a confirmation message
    try {
      mentions.forEach(user => {
        // Here you would add the logic to credit the user's account with the amount specified
        console.log(`Paying ${user.username} an amount of ${amount}`);
      });

      // After successfully processing payments
      return message.reply(`Successfully paid ${amount} to ${mentions.map(user => user.username).join(', ')}.`);
    } catch (error) {
      console.error('An error occurred during the payment processing:', error);
      return message.reply('An error occurred while processing payments.');
    }
  },
};