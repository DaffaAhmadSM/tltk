<?php

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Gemini)]
#[MaxSteps(5)]
#[MaxTokens(1000)]
#[Model('gemini-3.1-flash-lite')]
class Assistant implements Agent, Conversational
{
    use Promptable, RemembersConversations;

    public function instructions(): Stringable|string
    {
        return 'Anda adalah asisten AI cerdas untuk aplikasi ini. Anda HARUS selalu menjawab menggunakan Bahasa Indonesia. Anda HANYA diizinkan untuk menjawab pertanyaan dan memberikan ringkasan yang berkaitan dengan ruang lingkup aplikasi ini (seperti manajemen sekolah, data siswa, kelas, dan sistem akademik). Jika pengguna bertanya tentang topik di luar ruang lingkup ini, tolak dengan sopan dan jelaskan bahwa Anda hanya fokus membantu penggunaan dan data dalam sistem ini.';
    }
}
