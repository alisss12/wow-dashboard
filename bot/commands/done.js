const path = require('path');
const { ChannelType } = require('discord.js');
const mysql = require('mysql2/promise');

require('dotenv').config({ path: path.join(__dirname, '..', '.env') });

module.exports = {
  data: {
    name: 'done',
    description: 'Delete your finished M+ boost channel and save conversation (customer only)',
  },
  async execute(message) {
    if (message.author.bot) return;

    if (message.content.trim().toLowerCase() !== '!done') return;

    const channel = message.channel;

    if (!channel.name.startsWith('mplus-') || channel.type !== ChannelType.GuildText) {
      return;
    }

    console.log(`[!done] Attempt by ${message.author.tag} (${message.author.id}) in channel ${channel.name} (${channel.id})`);

    let pool;
    try {
      pool = mysql.createPool({
        host: process.env.DB_HOST,
        user: process.env.DB_USER,
        password: process.env.DB_PASSWORD,
        database: process.env.DB_NAME,
        connectionLimit: 10,
      });

      const [rows] = await pool.query(
        'SELECT customer_id FROM mplus_orders WHERE channel_id = ?',
        [channel.id]
      );

      if (rows.length === 0) {
        await message.reply('This channel is not linked to any M+ order.');
        return;
      }

      const storedCustomerId = rows[0].customer_id.toString();
      const authorId = message.author.id;

      if (storedCustomerId !== authorId) {
        await message.reply('❌ Only the **customer** who ordered this boost can use `!done`.');
        return;
      }

      // --- SAVE CHANNEL LOGS ---
      const messages = await channel.messages.fetch({ limit: 100 });
      const logEntries = [];

      for (const msg of messages.values()) {
        logEntries.push([
          null, // order_id (optional)
          channel.id,
          msg.id,
          msg.author.id,
          msg.author.username,
          msg.author.displayAvatarURL({ format: 'png', size: 128 }),
          msg.content || null,
          msg.createdAt,
          JSON.stringify(msg.attachments.map(a => a.url)),
          JSON.stringify(msg.embeds.map(e => e.toJSON())),
          JSON.stringify(Array.from(msg.reactions.cache.values()).map(r => ({
            emoji: r.emoji.name,
            count: r.count
          })))
        ]);
      }

      if (logEntries.length > 0) {
        // Correct bulk insert for MariaDB/mysql2
        const placeholders = logEntries.map(() => '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)').join(', ');
        const flatValues = logEntries.flat();

        await pool.query(
          `INSERT INTO mplus_channel_logs 
          (order_id, channel_id, message_id, author_id, author_username, author_avatar, content, timestamp, attachments, embeds, reactions)
          VALUES ${placeholders}`,
          flatValues
        );

        console.log(`[!done] Saved ${logEntries.length} messages from channel ${channel.id}`);
      }

      // --- Delete channel ---
      await channel.delete();
      await message.author.send('✅ Your M+ boost channel has been deleted and conversation saved!');

    } catch (error) {
      console.error('Error in !done command:', error);
      await message.reply('An error occurred while processing `!done`.');
    } finally {
      if (pool) await pool.end();
    }
  },
};