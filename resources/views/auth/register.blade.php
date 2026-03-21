<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="block w-full mt-2 rounded-lg border border-[#333] bg-[#222] px-4 py-2.5 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="block w-full mt-2 rounded-lg border border-[#333] bg-[#222] px-4 py-2.5 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="block w-full mt-2 rounded-lg border border-[#333] bg-[#222] px-4 py-2.5 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="block w-full mt-2 rounded-lg border border-[#333] bg-[#222] px-4 py-2.5 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Account Type -->
        <div>
            <label for="account_type" class="block text-sm font-medium text-gray-300">Account Type</label>
            <select id="account_type" name="account_type" required
                    class="block w-full mt-2 rounded-lg border border-[#333] bg-[#222] px-4 py-2.5 text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="user">Normal Player</option>
                <option value="booster">Booster</option>
                <option value="advertiser">Advertiser</option>
            </select>
            <x-input-error :messages="$errors->get('account_type')" class="mt-2 text-red-500 text-sm" />
        </div>

        <div>
            <button type="submit" class="flex w-full justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition tracking-wide">
                Register Account
            </button>
        </div>
        
        <p class="mt-8 text-center text-sm text-gray-400">
            Already registered?
            <a href="{{ route('login') }}" class="font-semibold leading-6 text-indigo-400 hover:text-indigo-300 transition">Sign in</a>
        </p>
    </form>
</x-guest-layout>
