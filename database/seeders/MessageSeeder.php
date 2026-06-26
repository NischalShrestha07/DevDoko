<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 2) {
            return;
        }

        $conversations = [
            [
                'messages' => [
                    'Hey bro! Timro Laravel project dekhe ramro lagyo. Khalti payment integration kasari gareu?',
                    'Thanks! Maile custom package banaye, Khalti API ko wrapper. Euta trait banayera controller ma use gare.',
                    'Tyo package open source cha ki? Malai ni mero project ma integrate garna man cha.',
                    'Hajur, GitHub ma rakhchu 2-4 din ma. Mailey yeta internal use ko lagi banako ho. Share garchu.',
                ],
            ],
            [
                'messages' => [
                    'Have you worked with React Server Components yet?',
                    'Yes, I tried them in a recent project. They are pretty cool for data fetching.',
                    'Any gotchas I should watch out for? Nepal ma hosted app ma kasto performance aauxa hola?',
                    'Performance ta ramro cha actually — server ma render hune, client lai bundle size kam. Just make sure you understand the client/server boundary.',
                ],
            ],
            [
                'messages' => [
                    'Docker optimization article dherai ramro theyo! Maile ni test garera herchu.',
                    'Thanks! Maile dherai optimization gareko chu multi-stage builds ra Alpine images use garera.',
                    'Mero app ma deployment garne bela permission denied error aauxa. K solution hola?',
                    'Storage ko permission check gara — `php artisan storage:link`, `chmod -R 775 storage` ani `chown www-data` set gara.',
                ],
            ],
        ];

        foreach ($conversations as $convIndex => $conversation) {
            $sender = $users->get($convIndex % $users->count());
            $receiver = $users->get(($convIndex + 1) % $users->count());

            if ($sender->id === $receiver->id) {
                $receiver = $users->first(function ($u) use ($sender) {
                    return $u->id !== $sender->id;
                });
                if (!$receiver) continue;
            }

            foreach ($conversation['messages'] as $msgIndex => $content) {
                $isSender = $msgIndex % 2 === 0;
                $createdAt = now()->subDays(count($conversation['messages']) - $msgIndex);

                Message::create([
                    'sender_id' => $isSender ? $sender->id : $receiver->id,
                    'receiver_id' => $isSender ? $receiver->id : $sender->id,
                    'content' => $content,
                    'type' => 'text',
                    'read_at' => $msgIndex < count($conversation['messages']) - 1 ? $createdAt : null,
                    'delivered_at' => $createdAt,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        $user1 = $users->get(0);
        $user2 = $users->get(min(1, $users->count() - 1));

        if ($user1->id !== $user2->id) {
            $codeMsg = Message::create([
                'sender_id' => $user1->id,
                'receiver_id' => $user2->id,
                'content' => 'Yo helper function — validation ko lagi:',
                'type' => 'code',
                'code_snippet' => "function validate(array \$d): array\n{\n    return validator(\$d, [\n        'email' => 'email|unique:users',\n        'phone' => 'phone:NEPAL',\n    ])->validate();\n}",
                'code_language' => 'php',
                'read_at' => null,
                'delivered_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
