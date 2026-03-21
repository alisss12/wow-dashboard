// commands/delrio.js
module.exports = {
    data: {
        name: 'delrio',
        description: 'Delete one of your registered Raider.IO characters'
    },
    async execute(message, args) {
        if (args.length < 2) {
            return message.reply('Usage: `!delrio <name> <realm>`\nExample: `!delrio Triggus Drak\'thul`');
        }

        const name = args[0];
        const realm = args.slice(1).join(' '); // in case realm has spaces

        const discordId = message.author.id;

        try {
            const response = await axios.post('http://152.228.154.27/wowbot/raiderio/riader/delete.php', {
                discord_id: discordId,
                name: name,
                realm: realm
            });

            if (response.data.success) {
                // Optional: remove roles (you can keep or remove this)
                const rolesToRemove = response.data.roles || [];
                for (const roleName of rolesToRemove) {
                    try {
                        const role = message.guild.roles.cache.find(r => r.name === roleName);
                        if (role) await message.member.roles.remove(role);
                    } catch (e) {
                        // ignore if role not found or missing perms
                    }
                }

                await message.reply(`**Character deleted successfully!**\n${name} - ${realm} is no longer registered to you.`);
            } else {
                await message.reply(response.data.error || 'Failed to delete character.');
            }
        } catch (error) {
            console.error('Delete error:', error.message);
            await message.reply('Error contacting server. Try again later.');
        }
    }
};