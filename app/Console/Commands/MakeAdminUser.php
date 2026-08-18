<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Création du compte administrateur.
 *
 * L'inscription publique étant désactivée, c'est le seul chemin prévu pour
 * créer un administrateur. `is_admin` est volontairement absent de $fillable
 * sur le modèle User : un User::create([... 'is_admin' => true]) l'ignorerait
 * en silence et produirait un compte sans droits.
 */
class MakeAdminUser extends Command
{
    protected $signature = 'portfolio:make-admin
                            {email : Adresse email du compte}
                            {--name= : Nom affiché (pour une création)}
                            {--password= : Mot de passe (demandé si absent)}';

    protected $description = "Crée un administrateur, ou promeut un compte existant";

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->is_admin = true;
            $user->save();

            $this->info("✓ {$email} est désormais administrateur.");

            return self::SUCCESS;
        }

        $name = $this->option('name') ?: $this->ask('Nom affiché');
        $password = $this->option('password') ?: $this->secret('Mot de passe');

        $validator = Validator::make(
            compact('name', 'email', 'password'),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->is_admin = true;
        $user->save();

        $this->info("✓ Administrateur {$email} créé. Connexion sur /se_logger.");

        return self::SUCCESS;
    }
}
