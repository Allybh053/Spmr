<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Server; // Adjust the namespace as per your application
use Faker\Factory as Faker;

class ServerSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the number of servers you want to create
        $numberOfServers = 10; // Adjust as needed

        // Loop to create multiple servers with faker data
        for ($i = 0; $i < $numberOfServers; $i++) {
            Server::create([
                'hostname' => $this->generateHostname(),
                'ipaddress' => $this->faker->ipv4,
                'sshusername' => $this->faker->userName,
                'sshkey' => $this->faker->password,
                'status' => $this->faker->numberBetween(0, 1), // Assuming status is either 0 or 1
            ]);
        }
    }

    /**
     * Generate a hostname starting with "postmaster"
     *
     * @return string
     */
    private function generateHostname()
    {
        $hostname = 'postmaster' . $this->faker->regexify('[A-Za-z0-9]{5}') . '@gov.com.my';
        return $hostname;
    }
    
}

