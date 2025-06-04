<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users_microsoft', function (Blueprint $table) {
            $table->id();

            // Identificador único da conta Microsoft
            $table->string('microsoft_id')->unique()->comment('ID único da conta Microsoft');

            // Dados pessoais
            $table->string('display_name')->nullable()->comment('Nome completo do usuário');
            $table->string('given_name')->nullable()->comment('Primeiro nome');
            $table->string('surname')->nullable()->comment('Sobrenome');

            // Dados de contato
            $table->string('user_principal_name')->nullable()->comment('Username ou e-mail principal');
            $table->string('mail')->nullable()->comment('Endereço de e-mail');
            $table->string('mobile_phone')->nullable()->comment('Telefone móvel');
            $table->json('business_phones')->nullable()->comment('Telefones comerciais');

            // Informações organizacionais
            $table->string('job_title')->nullable()->comment('Cargo');
            $table->string('company_name')->nullable()->comment('Empresa');
            $table->string('office_location')->nullable()->comment('Local de trabalho');

            // Preferências
            $table->string('preferred_language')->nullable()->comment('Idioma preferido');

            // Tokens de autenticação
            $table->text('microsoft_access_token')->nullable()->comment('Token de acesso Microsoft');
            $table->text('microsoft_refresh_token')->nullable()->comment('Token de atualização Microsoft');
            $table->timestamp('microsoft_token_expires_at')->nullable()->comment('Data de expiração do token');

            // Foto de perfil
            $table->string('microsoft_avatar')->nullable()->comment('URL ou referência da foto de perfil');

            // Timestamps padrão
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_microsoft');
    }
};
