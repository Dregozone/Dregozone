<?php

test('privacy policy mentions backup retention and security measures', function () {
    $this->get('/privacy-policy')
        ->assertSuccessful()
        ->assertSeeText('Operational database backups')
        ->assertSeeText('up to 2 years')
        ->assertSeeText('transactional email provider');
});
