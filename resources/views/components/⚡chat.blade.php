<?php

use Livewire\Component;
use App\Ai\Agents\Assistant;
use App\Models\Users;
use Illuminate\Support\Facades\Session;

new class extends Component
{
    public bool $floating = false;
    public string $prompt = '';
    public array $messages = [];
    public ?string $conversationId = null;


    public function submit()
    {
        $this->validate([
            'prompt' => 'required|string|max:1000'
        ]);

        $userInput = $this->prompt;
        $this->messages[] = ['role' => 'user', 'content' => $userInput];
        $this->prompt = '';

        $userId = Session::get('SESSION_USER_ID');
        $user = Users::find($userId);

        if (! $user) {
            return;
        }

        $agent = new Assistant();

        try {
            if ($this->conversationId) {
                $response = $agent->continue($this->conversationId, as: $user)->prompt($userInput);
            } else {
                $response = $agent->forUser($user)->prompt($userInput);
                $this->conversationId = $response->conversationId;
            }

            $this->messages[] = ['role' => 'assistant', 'content' => (string) $response];
        } catch (\Laravel\Ai\Exceptions\RateLimitedException $e) {
            $this->messages[] = ['role' => 'assistant', 'content' => 'Maaf, saya sedang menerima terlalu banyak permintaan. Silakan coba lagi beberapa saat.'];
        } catch (\Exception $e) {
            $this->messages[] = ['role' => 'assistant', 'content' => $e->getMessage()];
        }
    }
};
?>

<div @if($floating) x-data="{ open: false }" class="fixed bottom-6 right-6 z-[9999]" @endif>

    @if($floating)
    <!-- Floating toggle button -->
    <button @click="open = !open" class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-blue-700 transition-transform hover:scale-105 focus:outline-none z-50">
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
        <svg x-cloak x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    @endif

    <!-- Chat Box -->
    <div
        @if($floating)
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            class="absolute bottom-20 right-0 w-[380px] mb-2 origin-bottom-right"
            x-cloak
        @endif
    >
        <div class="flex flex-col {{ $floating ? 'h-[550px] shadow-2xl' : 'h-[600px] max-w-4xl mx-auto shadow-sm' }} border border-gray-200 rounded-2xl bg-white overflow-hidden font-sans">
            <!-- Header -->
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-sm">
                        AI
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 leading-tight">Asisten AI</h3>
                        <p class="text-xs text-gray-500">TALENTAKU</p>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 p-5 overflow-y-auto space-y-5 bg-white flex flex-col-reverse" id="chat-messages">
                <!-- Loading State -->
                <div wire:loading wire:target="submit" class="flex justify-start mb-4">
                    <div class="max-w-[85%] rounded-2xl px-5 py-3 shadow-sm bg-gray-50 border border-gray-100 text-gray-500 rounded-bl-none flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mengetik...
                    </div>
                </div>

                <div class="space-y-5 w-full">
                    @if(empty($messages))
                        <div class="flex flex-col items-center justify-center text-gray-400 space-y-3 mt-10">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="text-sm text-center">Tanyakan sesuatu seputar sistem TALENTAKU.</p>
                        </div>
                    @endif

                    @foreach($messages as $message)
                        <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[85%] rounded-2xl px-4 py-3 shadow-sm {{ $message['role'] === 'user' ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                                <div class="prose prose-sm max-w-none {{ $message['role'] === 'user' ? 'prose-invert text-white' : 'text-gray-800' }}">
                                    {!! nl2br(e($message['content'])) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Input Area -->
            <div class="border-t border-gray-100 p-4 bg-white">
                <form wire:submit="submit" class="flex gap-2 relative">
                    <input
                        type="text"
                        wire:model="prompt"
                        placeholder="Ketik pesan..."
                        class="flex-1 rounded-full border border-gray-300 bg-gray-50 pl-4 pr-12 py-3 text-sm text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all shadow-sm"
                        required
                        autocomplete="off"
                    >
                    <button
                        type="submit"
                        class="absolute right-1 top-1 bottom-1 bg-blue-600 text-white w-10 flex items-center justify-center rounded-full hover:bg-blue-700 transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                        wire:loading.attr="disabled"
                    >
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.class="hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
