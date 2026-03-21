const { EmbedBuilder, PermissionsBitField } = require('discord.js');
const dbConfig = require('../config');
const mysql = require('mysql2/promise');

const activeSquads = new Map(); // In-memory store: advertiserId -> squad data

module.exports = {
  data: {
    name: 'team',
    description: "Create a team squad or let Admins view an Advertiser's team.",
  },
  async execute(message) {
    if (!message.guild) {
      await message.reply('This command can only be used in a server.');
      return;
    }

    const args = message.content.split(/\s+/).slice(1);
    const mentions = message.mentions.users;

    // Admin Check: !team @advertiser
    if (args.length === 1 && mentions.size === 1) {
      const adminRole = message.guild.roles.cache.find(role => role.name === 'Admin');
      if (!adminRole || !message.member.roles.cache.has(adminRole.id)) {
        return message.reply("Only Admins can check an advertiser's team.");
      }

      const targetAdvertiserId = mentions.first().id;
      const squadData = activeSquads.get(targetAdvertiserId);

      if (!squadData) {
        return message.reply(`No active Mythic+ squad found for advertiser <@${targetAdvertiserId}>.`);
      }

      // Send the squad details direct to the admin
      const adminEmbed = new EmbedBuilder()
        .setTitle(`📊 Squad Details for ${mentions.first().username}`)
        .setDescription(squadData.verboseDetails.join('\n\n'))
        .setColor('#ff0000')
        .setFooter({ text: 'Admin Squad Viewer' });

      return message.reply({ embeds: [adminEmbed] });
    }

    // 1. Permission Check for Advertiser Creating a Team
    const advertiserRole = message.guild.roles.cache.find((role) => role.name === 'Advertiser');
    if (!advertiserRole || !message.member.roles.cache.has(advertiserRole.id)) {
      await message.reply('You need to have the "Advertiser" role to use this command.');
      return;
    }

    const squadMembers = [];

    // Global match to handle format: <@1234567890>:tank OR <@1234567890> :tank
    const mentionRegex = /<@!?(\d+)>\s*:(tank|heal|dps)/gi;
    let match;

    while ((match = mentionRegex.exec(message.content)) !== null) {
      squadMembers.push({
        id: match[1],
        requestedRole: match[2].toLowerCase()
      });
    }

    // Check if we have between 2 and 5 players
    if (squadMembers.length < 2 || squadMembers.length > 5) {
      return message.reply(`You need to successfully tag between 2 and 5 team members alongside their role.
**Usage Example:** \`!team @player1:tank @player2 :heal @player3:dps\``);
    }

    try {
      // Connect to Database
      const connection = await mysql.createConnection(dbConfig);

      let totalScore = 0;
      let validMembersCount = 0;
      let squadDetails = [];
      let verboseSquadDetails = []; // Holds the detailed stats strings

      // 3. Query Database for each mentioned user and requested score
      for (const member of squadMembers) {
        const userId = member.id;
        const reqRole = member.requestedRole;

        // Define which score column to pull based on the manual role requested
        let scoreColumn = '';
        let roleEmoji = '👤';
        let roleDisplay = '';

        if (reqRole === 'tank') {
          scoreColumn = 'score_tank';
          roleEmoji = '🛡️';
          roleDisplay = 'Tank';
        } else if (reqRole === 'heal') {
          scoreColumn = 'score_healer';
          roleEmoji = '💚';
          roleDisplay = 'Healer';
        } else if (reqRole === 'dps') {
          scoreColumn = 'score_dps';
          roleEmoji = '⚔️';
          roleDisplay = 'DPS';
        }

        const query = `SELECT name, class, realm, region, score_all, ${scoreColumn} as role_score FROM discord_heroes WHERE discord_id = ? ORDER BY ${scoreColumn} DESC LIMIT 1`;

        const [rows] = await connection.execute(query, [userId]);

        const userObj = message.client.users.cache.get(userId);
        const username = userObj ? userObj.username : 'Unknown User';

        if (rows.length > 0) {
          const hero = rows[0];
          // Since some players might have a 0 score for off-roles, fallback to 0
          const score = parseFloat(hero.role_score) || 0;
          totalScore += score;
          validMembersCount++;

          squadDetails.push(
            `**${roleEmoji} <@${userId}> [${roleDisplay}]** \n└ ${hero.name}-${hero.realm} (${hero.class}) - **${score.toFixed(1)} IO**`
          );

          // Add to verbose details for the button
          const region = hero.region || 'eu'; // Fallback to EU if not in DB
          verboseSquadDetails.push(
            `**<@${userId}>** - [${hero.name}](https://raider.io/characters/${region}/${hero.realm}/${hero.name})
└ **Class:** ${hero.class} | **Realm:** ${hero.realm} | **Overall IO:** ${parseFloat(hero.score_all || 0).toFixed(1)}`
          );
        } else {
          squadDetails.push(`**${roleEmoji} <@${userId}> [${roleDisplay}]** \n└ ⚠️ No Character Data Found`);
          verboseSquadDetails.push(`**<@${userId}>** \n└ ⚠️ No Character Data Found (Not linked to Discord)`);
        }
      }

      // Save squad to database for cross-command continuity
      await connection.execute(`
        CREATE TABLE IF NOT EXISTS active_mplus_squads (
          advertiser_id VARCHAR(255) PRIMARY KEY,
          squad_json TEXT,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
      `);

      const squadData = {
        verboseDetails: verboseSquadDetails,
        members: squadMembers
      };

      await connection.execute(
        `REPLACE INTO active_mplus_squads (advertiser_id, squad_json) VALUES (?, ?)`,
        [message.author.id, JSON.stringify(squadData)]
      );

      await connection.end();

      // 4. Calculate Team Score (Average)
      const teamAverageIO = validMembersCount > 0 ? (totalScore / validMembersCount).toFixed(1) : 0;

      // 5. Build Embed
      const embed = new EmbedBuilder()
        .setTitle('🏆 Mythic+ Squad Formed!')
        .setDescription(`**Combined Avg Team Score:** \`${teamAverageIO} IO\`\n\n**Squad Roster:**\n\n${squadDetails.join('\n\n')}`)
        .setColor('#FFD700')
        .setAuthor({ name: message.author.username, iconURL: message.author.displayAvatarURL() })
        .setThumbnail('https://i.imgur.com/YVbNLVA.jpeg')
        .setFooter({ text: 'GodOfWar BOT Squad Builder', iconURL: 'https://i.imgur.com/YVbNLVA.jpeg' })
        .setTimestamp();

      // Save to memory for Admins to view later
      activeSquads.set(message.author.id, squadData);

      // Create a View Details button
      const { ActionRowBuilder, ButtonBuilder, ButtonStyle } = require('discord.js');
      const viewDetailsBtn = new ButtonBuilder()
        .setCustomId('view_team_details')
        .setLabel('🔍 View Detailed Stats')
        .setStyle(ButtonStyle.Secondary);

      const row = new ActionRowBuilder().addComponents(viewDetailsBtn);

      // 6. Post Embed
      const sentMessage = await message.channel.send({ embeds: [embed], components: [row] });

      // 7. Handle Button Click - Restrict to Advertiser who created it OR Admins
      const filter = (i) => {
        if (i.customId !== 'view_team_details') return false;

        // Grant access if they are the original author
        if (i.user.id === message.author.id) return true;

        // Grant access if they have the 'Admin' role
        const adminRole = i.guild.roles.cache.find(role => role.name === 'Admin');
        if (adminRole && i.member.roles.cache.has(adminRole.id)) return true;

        return false;
      };

      const collector = sentMessage.createMessageComponentCollector({ filter, time: 300000 }); // 5 minutes

      collector.on('collect', async (i) => {
        try {
          const detailEmbed = new EmbedBuilder()
            .setTitle('📊 Squad Detailed Stats')
            .setDescription(verboseSquadDetails.join('\n\n'))
            .setColor('#0099ff')
            .setFooter({ text: 'Details are only visible to the advertiser.' });

          // Reply ephemerally so only the person who clicked it sees the details
          if (!i.replied && !i.deferred) {
            await i.reply({ embeds: [detailEmbed], ephemeral: true });
          }
        } catch (err) {
          console.error('Interaction Reply Error:', err);
        }
      });

      collector.on('end', () => {
        // Disable button after 5 minutes
        viewDetailsBtn.setDisabled(true);
        sentMessage.edit({ components: [new ActionRowBuilder().addComponents(viewDetailsBtn)] }).catch(() => { });
      });

    } catch (error) {
      console.error('Database query error:', error);
      await message.reply('An error occurred while fetching data from the database.');
    }
  },
};
