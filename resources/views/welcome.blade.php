<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GOW Boosting | #1 Elite WoW Community</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (via CDN for rapid styling) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            gold: '#f8b803',
                            blue: '#6366f1',
                            dark: '#0f111a',
                            darker: '#050505',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .hero-bg {
            background: linear-gradient(to bottom, rgba(5,5,5,0.7), #0f111a), url('/images/hero-elite-bg.png');
            background-size: cover;
            background-position: center;
        }
        .glow-cyan { text-shadow: 0 0 20px rgba(99, 102, 241, 0.4); }
        .glow-gold { text-shadow: 0 0 20px rgba(248, 184, 3, 0.4); }
        .card-glass {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .hover-card:hover {
            border-color: rgba(99, 102, 241, 0.4);
            background: rgba(99, 102, 241, 0.03);
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-brand-darker text-gray-400 font-sans selection:bg-brand-blue selection:text-white">

    <!-- NAVIGATION -->
    <header class="fixed w-full top-0 z-50 transition-all duration-300 bg-brand-darker/80 backdrop-blur-md border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-2 group">
                <div class="w-8 h-8 bg-brand-gold rounded-lg flex items-center justify-center transform group-hover:rotate-12 transition">
                    <span class="text-brand-dark font-black text-xl italic">G</span>
                </div>
                <span class="text-white font-black text-2xl uppercase tracking-tighter italic glow-gold">GOW <span class="text-brand-gold">Boosting</span></span>
            </div>
            
            <nav class="hidden md:flex items-center space-x-10 text-[10px] font-black uppercase tracking-widest">
                <a href="#services" class="hover:text-brand-gold transition">Our Services</a>
                <a href="#recruitment" class="hover:text-brand-gold transition">Join the Team</a>
                <a href="#stats" class="hover:text-brand-gold transition">The Community</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-brand-blue/10 border border-brand-blue/30 text-brand-blue px-6 py-2.5 rounded-lg hover:bg-brand-blue hover:text-white transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-white transition">Log In</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-brand-gold text-brand-dark px-6 py-2.5 rounded-lg hover:bg-white transition shadow-lg shadow-brand-gold/20">Get Started</a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden hero-bg">
        <div class="absolute inset-0 bg-gradient-to-t from-brand-darker via-transparent to-transparent"></div>
        
        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center pt-20">
            <div class="inline-flex items-center space-x-2 bg-brand-gold/5 border border-brand-gold/20 rounded-full px-4 py-1.5 mb-8 animate-bounce">
                <span class="text-brand-gold text-[8px] font-black uppercase tracking-widest italic">Now Recruiting Active Boosters</span>
            </div>
            
            <h1 class="text-6xl md:text-8xl font-black text-white italic uppercase tracking-tighter leading-none mb-6">
                Dominate the <span class="text-brand-blue glow-cyan italic">Shadows</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-400 font-medium max-w-2xl mx-auto mb-12">
                The most secure, gold-only World of Warcraft boosting community. Elite players, 24/7 support, and guaranteed operational excellence.
            </p>
            
            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                <a href="{{ route('register') }}" class="w-full md:w-auto bg-brand-blue px-10 py-5 rounded-2xl text-white font-black text-sm uppercase tracking-widest hover:bg-indigo-500 transition shadow-xl shadow-brand-blue/20 active:scale-95">
                    Join the Elite
                </a>
                <a href="#services" class="w-full md:w-auto bg-white/5 border border-white/10 px-10 py-5 rounded-2xl text-white font-black text-sm uppercase tracking-widest hover:bg-white/10 transition backdrop-blur-md active:scale-95">
                    Explore Services
                </a>
            </div>
        </div>

        <!-- SCROLL INDICATOR -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 opacity-20 hidden md:block">
            <div class="w-px h-24 bg-gradient-to-b from-white to-transparent"></div>
        </div>
    </section>

    <!-- STATS BAND -->
    <section id="stats" class="py-20 border-y border-white/5 bg-brand-dark">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 lg:grid-cols-4 gap-12 text-center">
            <div class="space-y-2">
                <h3 class="text-5xl font-black text-white italic tracking-tighter glow-gold">50K+</h3>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Active Members</p>
            </div>
            <div class="space-y-2">
                <h3 class="text-5xl font-black text-white italic tracking-tighter glow-cyan">5M+</h3>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Successful Carries</p>
            </div>
            <div class="space-y-2">
                <h3 class="text-5xl font-black text-white italic tracking-tighter glow-gold">24/7</h3>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Live Integration</p>
            </div>
            <div class="space-y-2">
                <h3 class="text-5xl font-black text-white italic tracking-tighter glow-cyan">100%</h3>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Gold Compliance</p>
            </div>
        </div>
    </section>

    <!-- SERVICES -->
    <section id="services" class="py-32 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-xs font-black text-brand-blue uppercase tracking-[0.3em] mb-4 italic">Core Protocol Services</h2>
                <h3 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter">Specialized Deployments</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- SERVICE 1 -->
                <div class="card-glass hover-card p-10 rounded-[2.5rem] transition duration-500 group">
                    <div class="w-16 h-16 bg-brand-blue/10 border border-brand-blue/20 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h4 class="text-2xl font-black text-white uppercase italic mb-4">Mythic+ Tactical</h4>
                    <p class="text-sm leading-relaxed mb-8">Timed runs for any key level. Secure your weekly Great Vault rewards and optimize your IO score with veteran dungeon masters.</p>
                    <ul class="space-y-3 mb-10 text-[10px] font-bold uppercase tracking-widest text-gray-500">
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-brand-blue rounded-full mr-2"></span> Key Levels 2-25+</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-brand-blue rounded-full mr-2"></span> Specific Loot Traded</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-brand-blue rounded-full mr-2"></span> Fast, 15m Start</li>
                    </ul>
                    <a href="#" class="inline-block text-[10px] font-black uppercase tracking-widest text-white border-b-2 border-brand-blue pb-1 hover:text-brand-blue transition">View Pricing</a>
                </div>

                <!-- SERVICE 2 -->
                <div class="card-glass hover-card p-10 rounded-[2.5rem] transition duration-500 group border-brand-gold/10">
                    <div class="w-16 h-16 bg-brand-gold/10 border border-brand-gold/20 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h4 class="text-2xl font-black text-white uppercase italic mb-4">Raid Operations</h4>
                    <p class="text-sm leading-relaxed mb-8">Full clears across Normal, Heroic, and Mythic difficulties. Guaranteed loot drops, ahead-of-the-curve carries, and mythic mounts.</p>
                    <ul class="space-y-3 mb-10 text-[10px] font-bold uppercase tracking-widest text-gray-500">
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-brand-gold rounded-full mr-2"></span> AOTC & Cutting Edge</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-brand-gold rounded-full mr-2"></span> Full Armor Class Stacks</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-brand-gold rounded-full mr-2"></span> Mythic Mount Runs</li>
                    </ul>
                    <a href="{{ route('raids.index') }}" class="inline-block text-[10px] font-black uppercase tracking-widest text-white border-b-2 border-brand-gold pb-1 hover:text-brand-gold transition">View Schedule</a>
                </div>

                <!-- SERVICE 3 -->
                <div class="card-glass hover-card p-10 rounded-[2.5rem] transition duration-500 group">
                    <div class="w-16 h-16 bg-brand-blue/10 border border-brand-blue/20 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h4 class="text-2xl font-black text-white uppercase italic mb-4">PvP Domination</h4>
                    <p class="text-sm leading-relaxed mb-8">Arena rating boosts, gladiator pushes, and high-tier coaching. Dominate the ladder with the top 0.1% PvP specialists.</p>
                    <ul class="space-y-3 mb-10 text-[10px] font-bold uppercase tracking-widest text-gray-500">
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-brand-blue rounded-full mr-2"></span> 1800-2400 Rating Boosts</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-brand-blue rounded-full mr-2"></span> Gladiator Mount Coaching</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-brand-blue rounded-full mr-2"></span> VOD Review & Coaching</li>
                    </ul>
                    <a href="#" class="inline-block text-[10px] font-black uppercase tracking-widest text-white border-b-2 border-brand-blue pb-1 hover:text-brand-blue transition">View PvP Plans</a>
                </div>
            </div>
        </div>
    </section>

    <!-- RECRUITMENT -->
    <section id="recruitment" class="py-32 bg-[#0c0e14]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-20">
                <div class="lg:w-1/2">
                    <h2 class="text-xs font-black text-brand-gold uppercase tracking-[0.3em] mb-4 italic">Join the Operation</h2>
                    <h3 class="text-5xl font-black text-white uppercase italic tracking-tighter leading-tight mb-8">Ready to Scale Your Influence?</h3>
                    <p class="text-lg text-gray-500 mb-12">We are constantly seeking the top 1% of World of Warcraft talent. Whether you control a high-end mythic raid team or are a master of sales and advertising, there's a node waiting for you.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="card-glass p-8 rounded-3xl group hover:bg-brand-gold/5 transition">
                            <h4 class="text-white font-black uppercase text-xs mb-2">Apply as Booster</h4>
                            <p class="text-[11px] font-medium leading-relaxed mb-6">Gain access to a massive stream of customers and organized raid schedules.</p>
                            <a href="{{ route('register') }}" class="text-[10px] font-black text-brand-gold uppercase tracking-widest flex items-center">
                                Join as Booster <svg class="w-3 h-3 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                        <div class="card-glass p-8 rounded-3xl group hover:bg-brand-blue/5 transition">
                            <h4 class="text-white font-black uppercase text-xs mb-2">Apply as Advertiser</h4>
                            <p class="text-[11px] font-medium leading-relaxed mb-6">Earn professional commissions by connecting customers to our elite roster.</p>
                            <a href="{{ route('register') }}" class="text-[10px] font-black text-brand-blue uppercase tracking-widest flex items-center">
                                Join as Advertiser <svg class="w-3 h-3 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="lg:w-1/2 relative">
                    <div class="aspect-square rounded-[3rem] overflow-hidden border border-white/5 shadow-2xl relative">
                        <img src="/images/hero-elite-bg.png" class="w-full h-full object-cover grayscale opacity-30 hover:grayscale-0 hover:opacity-100 transition duration-1000" alt="Recruitment">
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-darker to-transparent"></div>
                        <div class="absolute bottom-10 left-10 right-10 p-8 card-glass rounded-3xl border-indigo-500/20">
                            <span class="text-[9px] font-black text-brand-blue uppercase tracking-widest block mb-1 italic">Verified Testimonial</span>
                            <p class="text-xs text-white italic font-medium">"Joining GOW Boosting changed the game for my raid team. The organization and constant flow of work are unmatched in the industry."</p>
                            <div class="flex items-center mt-6">
                                <div class="w-8 h-8 rounded-full bg-brand-blue flex items-center justify-center mr-3 text-[10px] font-black text-white">Z</div>
                                <div>
                                    <span class="text-[10px] font-black text-white block">ZEPHYR</span>
                                    <span class="text-[8px] font-bold text-gray-500 italic lowercase tracking-wider">Mythic Guild Leader</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION (Discord) -->
    <section class="py-32">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-900 p-12 md:p-20 rounded-[4rem] text-center shadow-2xl shadow-indigo-500/20 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none transition duration-1000 group-hover:scale-110">
                    <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.419 0 1.334-.956 2.419-2.157 2.419zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.419 0 1.334-.946 2.419-2.157 2.419z"/></svg>
                </div>
                <h3 class="text-4xl font-black text-white italic uppercase mb-8 relative z-10 leading-tight">Ready to Deploy Your Order?</h3>
                <p class="text-indigo-100 font-medium mb-12 opacity-80 relative z-10">Join our Discord for instant price quotes, live operational support, and exclusive community giveaways.</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center relative z-10">
                    <a href="https://discord.gg/yourlink" class="bg-white text-brand-blue px-10 py-5 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl transition hover:bg-brand-gold hover:text-brand-dark active:scale-95">Open Comms Hub</a>
                    <a href="{{ route('register') }}" class="bg-brand-blue/20 border border-white/20 text-white px-10 py-5 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white/10 transition backdrop-blur-md active:scale-95">Create Account</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-20 border-t border-white/5 bg-[#050505]">
        <div class="max-w-7xl mx-auto px-6 flex flex-col items-center">
            <div class="flex items-center space-x-2 mb-10">
                <div class="w-6 h-6 bg-gray-900 rounded flex items-center justify-center">
                    <span class="text-gray-500 font-black text-xs italic">G</span>
                </div>
                <span class="text-gray-500 font-black text-lg uppercase tracking-tighter italic">GOW <span class="text-brand-gold opacity-50">Boosting</span></span>
            </div>
            
            <nav class="flex flex-wrap justify-center gap-x-8 gap-y-4 mb-10 text-[10px] font-black uppercase tracking-widest text-gray-700">
                <a href="#" class="hover:text-brand-gold transition">Privacy Protocol</a>
                <a href="#" class="hover:text-brand-gold transition">Terms of Service</a>
                <a href="#" class="hover:text-brand-gold transition">Site Map</a>
                <a href="#" class="hover:text-brand-gold transition">DMCA Policy</a>
            </nav>
            
            <p class="text-[9px] text-gray-800 font-bold uppercase tracking-widest text-center max-w-2xl">
                Copyright &copy; {{ date('Y') }} GOW Boosting. World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries.
            </p>
            <p class="text-[9px] text-brand-gold/20 font-black uppercase tracking-[0.5em] mt-8 italic">Gold-Only Transactions Supported</p>
        </div>
    </footer>

</body>
</html>
