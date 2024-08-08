<?php

  return [

    /*
     * OpenAI Model
     */
    'openapi_key' => env('OPENAI_API_KEY'),

    'model' => env('OPENAI_API_MODEL', 'gpt-4-turbo'),

    /*
     * OpenAI Assistant ID
     */
    'assistant_id' => env('SYNAPSE_ASSISTANT_ID'),

    /*
     * Prompt for the Assistant
     */
    'prompt' => env('SYNAPSE_PROMPT'),
  ];
