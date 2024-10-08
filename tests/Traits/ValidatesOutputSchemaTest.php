<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Traits\Agent\HandlesAgentEvents;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

test('Validates Output Schema', function (): void {

    Event::fake();

    class ValidatesOutputSchemaTestAgent extends Agent implements HasOutputSchema
    {
        use HandlesAgentEvents;
        use ValidatesOutputSchema;

        protected string $promptView = 'synapse::Tests.ValidatesOutputSchemaTestAgent';

        public function resolveIntegration(): Integration
        {
            return new OpenAIIntegration;
        }

        public function resolveOutputSchema(): array
        {
            return [
                SchemaRule::make([
                    'name' => 'pdfs.*',
                    'rules' => 'sometimes|array',
                    'description' => 'An array of PDF links extracted from the website content.',
                ]),
                SchemaRule::make([
                    'name' => 'pdfs.*.link',
                    'rules' => 'required|url',
                    'description' => 'The link URL for the PDF.',
                ]),
                SchemaRule::make([
                    'name' => 'pdfs.*.title',
                    'rules' => 'required|string',
                    'description' => 'The Title of the PDF (usually the link text) in title case.',
                ]),
                SchemaRule::make([
                    'name' => 'pdfs.*.category',
                    'rules' => 'required|string',
                    'description' => 'One of `Order Form`, `Technical Data`, `Catalog`, `Parts Manual`, `Brochure`, `Template`, `Miscellaneous`',
                ]),
                SchemaRule::make([
                    'name' => 'pdfs.*.product',
                    'rules' => 'sometimes|string',
                    'description' => 'If this PDF relates to a specific product put the name here.',
                ]),
                SchemaRule::make([
                    'name' => 'pdfs.*.product_category',
                    'rules' => 'sometimes|string',
                    'description' => 'One of `Doors`, `Frames`, `Door Assemblies`',
                ]),
            ];
        }
    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Traits/ValidatesOutputSchemaTestAgent-{$hash}");
        },
    ]);

    $agent = new ValidatesOutputSchemaTestAgent;
    $message = $agent->handle([
        'meta' => "Page Title: Pioneer Industries\nURL: https://www.pioneerindustries.com/products/systems",
        'input' => "- [Home](\/)\n- [About Pioneer](\/about-pioneer)\n- [Doors](\/products\/doors)\n- [Frames](\/products\/frames)\n- [Door Assemblies](\/products\/systems)\n- [Projects](\/projects)\n- [Find Distributors](\/distributors)\n- [Tech Data](\/resources)\n- [Specialty](\/specialty)\n- [ASTM E119](\/e119)\n- [Energy Efficient](\/energy-efficient)\n- [Customer Portal](\/customer-portal)\n- [Contact Us](\/contact-us)\n- [Search](\/search)\n\n# Pioneer Door Assemblies\n\n## ACOUSTICAL\n\n[![](https:\/\/www.pioneerindustries.com\/img\/fileicons\/pdf.png)\\\\\n\\\\\nACOUSTIC ASSEMBLIES](\/var\/uploads\/STCASSEMBLIESPIOSONICSERIES2018.pdf)\n\n## FEMA\n\n[![](https:\/\/www.pioneerindustries.com\/img\/fileicons\/pdf.png)\\\\\n\\\\\nTORNADO RESISTANT DOOR for RESIDENTIAL SAFE ROOMS COMPLIES WITH ICC 500](\/var\/uploads\/PiocaneFEMAICC500residentialsaferooms.pdf) [![](https:\/\/www.pioneerindustries.com\/img\/fileicons\/pdf.png)\\\\\n\\\\\nTORNADO RESISTANT DOOR for SHELTERS with SINGLE MOTION LOCKS COMPLIES WITH ICC 500](\/var\/uploads\/PiocaneFEMAICC500SHELTERSINGLEMOTIONLOCKS.pdf)\n\n## FLORIDA BUILDING CODE\n\n[![](https:\/\/www.pioneerindustries.com\/img\/fileicons\/pdf.png)\\\\\n\\+\/\\- 50 PSF HURRICANE RATED STOREFRONT ASSEMBLY](\/var\/uploads\/Piocane50StorefrontHVHZ11.pdf) [![](https:\/\/www.pioneerindustries.com\/img\/fileicons\/pdf.png)\\\\\n\\\\\n+\/-50 PSF HURRICANE RATED DOOR ASSEMBLY](\/var\/uploads\/PIOCANE50FG.pdf) [![](https:\/\/www.pioneerindustries.com\/img\/fileicons\/pdf.png)\\\\\n\\+\/\\- 70 PSF HURRICANE RATED DOOR ASSEMBLY](\/var\/uploads\/Piocane70HVHZ1.pdf) [![](https:\/\/www.pioneerindustries.com\/img\/fileicons\/pdf.png)\\\\\n\\+\/\\- 50 PSF HURRICANE RATED FULL GLASS DOOR ASSEMBLY](\/var\/uploads\/1-PIOCANE-50 FG.pdf)\n\n## BLAST RESISTANT\n\n[![](https:\/\/www.pioneerindustries.com\/img\/fileicons\/pdf.png)\\\\\n\\\\\nENGINEERING DETAILS for BLAST RESISTANT DOOR ASSEMBLY](\/var\/uploads\/SBR16.pdf)\n\n## BULLET RESISTANT\n\n[![](https:\/\/www.pioneerindustries.com\/img\/fileicons\/pdf.png)\\\\\n\\\\\nBULLET RESISTANT DOOR COMPLIES WITH UL 752 LEVEL 1 AND LEVEL3](\/var\/uploads\/BR752DOORASSEMBLY.pdf)\n\nPioneer Industries is a brand associated with AADG, Inc., an ASSA ABLOY Group company. Copyright \u00a9 2019, AADG, Inc. All rights reserved. Reproduction in whole or in part without the express written permission of AADG, Inc. is prohibited.\n\n- [CUSTOMER PORTAL](\/customer-portal)\n- [CONTACT US](\/contact-us)\n- [PRIVACY NOTICE](\/privacy-notice)\n- [DO NOT SELL MY PERSONAL INFORMATION](\/do-not-sell-my-personal-information)",
    ]);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('pdfs')
        ->and($agentResponseArray['content']['pdfs'])->toHaveCount(9);

});
