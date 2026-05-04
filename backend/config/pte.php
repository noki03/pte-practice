<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PTE Question Type Timing (seconds)
    |--------------------------------------------------------------------------
    */
    'timing' => [
        'read_aloud' => [
            'preparation' => 30,
            'response'    => 40,
        ],
        'repeat_sentence' => [
            'preparation' => 0,
            'response'    => 15,
        ],
        'describe_image' => [
            'preparation' => 25,
            'response'    => 40,
        ],
        're_tell_lecture' => [
            'preparation' => 10,
            'response'    => 40,
        ],
        'answer_short_question' => [
            'preparation' => 0,
            'response'    => 10,
        ],
        'summarize_written_text' => [
            'response' => 600,
        ],
        'write_essay' => [
            'response' => 1200,
        ],
        'summarize_spoken_text' => [
            'response' => 600,
        ],
        'write_from_dictation' => [
            'preparation' => 0,
            'response'    => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Scoring
    |--------------------------------------------------------------------------
    */
    'scoring' => [
        'min' => 10,
        'max' => 90,
        'ema_alpha' => 0.2,
        'score_history_limit' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Audio
    |--------------------------------------------------------------------------
    */
    'audio' => [
        'max_duration_ms'    => 45_000,
        'max_file_size_mb'   => 5,
        'accepted_mimes'     => ['audio/webm', 'video/webm', 'audio/ogg', 'audio/wav', 'audio/mp4', 'audio/mpeg'],
        'preferred_codec'    => 'audio/webm;codecs=opus',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache TTLs (seconds)
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'task_list'     => 3_600,
        'task_detail'   => 3_600,
        'scoring_rules' => 86_400,
        'skill_list'    => 86_400,
        'user_progress' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'slow_response_threshold_ms' => 200,
    ],

];
