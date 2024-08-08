<?php

  return [
    /*
     * AI Service
     */
    'ai_service' => env('SYNAPSE_AI_SERVICE', 'openai'),

    /*
     * API Key
     */
    'api_key' => env('SYNAPSE_API_KEY'),

    /*
     * OpenAI Model
     */
    'model' => env('SYNAPSE_MODEL'),

    /*
     * OpenAI Assistant ID
     */
    'assistant_id' => env('SYNAPSE_ASSISTANT_ID'),

    /*
     * Prompt for the Assistant
     */
    'prompt' => env('SYNAPSE_PROMPT'),
  ];
