<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportCountriesCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     *  Import all countries and cities from API and add them to the database
     */
    public function run(): void
    {
        $this->command->info('🌍 Starting to get countries and cities from API...');

        DB::transaction(function () {
            // Step 1: Get all countries from RestCountries API
            $this->command->info('📡 Getting countries from RestCountries API...');

            try {
                $response = Http::timeout(60)->get('https://restcountries.com/v3.1/all', [
                    'fields' => 'name,cca2,cca3'
                ]);

                if (!$response->successful()) {
                    $this->command->error('❌ Failed to get countries from API');
                    Log::error('ImportCountriesCitiesSeeder Error: Failed to get countries from API');
                    return;
                }

                $countriesData = $response->json();
                $this->command->info('✅ Got ' . count($countriesData) . ' countries');

                $countriesAdded = 0;
                $countriesSkipped = 0;

                // Step 2: Save the countries to the database
                foreach ($countriesData as $countryData) {
                    $code = $countryData['cca2'] ?? null;
                    $name = $countryData['name']['common'] ?? null;

                    if (!$code || !$name) {
                        continue;
                    }

                    // Use updateOrCreate to keep the existing data
                    $country = Country::updateOrCreate(
                        ['code' => $code],
                        [
                            'name' => $name,
                            'is_available' => true,
                        ]
                    );

                    if ($country->wasRecentlyCreated) {
                        $countriesAdded++;
                    } else {
                        $countriesSkipped++;
                    }
                }

                $this->command->info("✅ Added {$countriesAdded} new countries");
                $this->command->info("ℹ️  Skipped {$countriesSkipped} existing countries");

                // Step 3: Get the cities for each country
                $this->command->info('🏙️  Starting to get cities...');

                $this->importCitiesFromGeonames();

            } catch (\Exception $e) {
                $this->command->error('❌ Error: ' . $e->getMessage());
                Log::error('ImportCountriesCitiesSeeder Error: ' . $e->getMessage());
            }
        });

        $this->command->info('🎉 Done!');
    }

    /**
     * Import the cities from the local JSON file (better than API because it's faster and more stable)
     */
    private function importCitiesFromGeonames(): void
    {
        // We will use a specific list of countries and cities only
        // You can expand it later

        $countriesWithCities = [
            'EG' => ['Cairo', 'Alexandria', 'Giza', 'Shubra El-Kheima', 'Port Said', 'Suez', 'Luxor', 'Mansoura', 'Tanta', 'Asyut', 'Ismailia', 'Faiyum', 'Zagazig', 'Aswan', 'Damietta', 'Damanhur', 'Minya', 'Beni Suef', 'Qena', 'Sohag', 'Hurghada', 'Sharm El Sheikh', 'Marsa Alam', 'Dahab', 'Nuweiba', 'El Gouna', '6th of October City', 'New Cairo', 'Sheikh Zayed City', 'Madinaty'],
            'SA' => ['Riyadh', 'Jeddah', 'Mecca', 'Medina', 'Dammam', 'Khobar', 'Dhahran', 'Abha', 'Tabuk', 'Buraidah', 'Khamis Mushait', 'Hail', 'Hofuf', 'Mubarraz', 'Najran', 'Yanbu', 'Jizan', 'Al Qatif', 'Jubail', 'Arar', 'Sakakah', 'Al-Bahah', 'Taif', 'Al Kharj'],
            'AE' => ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain', 'Al Ain', 'Khor Fakkan', 'Dibba Al-Fujairah', 'Kalba', 'Jebel Ali', 'Dhaid'],
            'KW' => ['Kuwait City', 'Hawalli', 'Salmiya', 'Sabah Al-Salem', 'Farwaniya', 'Ahmadi', 'Jahra', 'Fahaheel', 'Mangaf', 'Abu Halifa', 'Fintas', 'Mahboula'],
            'BH' => ['Manama', 'Muharraq', 'Riffa', 'Hamad Town', 'Isa Town', 'Sitra', 'Budaiya', 'Jidhafs', 'Al-Malikiyah', 'Aali'],
            'OM' => ['Muscat', 'Salalah', 'Sohar', 'Nizwa', 'Sur', 'Ibri', 'Buraimi', 'Rustaq', 'Bahla', 'Khasab', 'Duqm'],
            'QA' => ['Doha', 'Al Rayyan', 'Umm Salal', 'Al Wakrah', 'Al Khor', 'Dukhan', 'Madinat ash Shamal', 'Mesaieed', 'Al Wukair'],
            'JO' => ['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Madaba', 'Jerash', 'Ajloun', 'Karak', 'Mafraq', 'Tafilah', 'Maan', 'Salt', 'Petra', 'Wadi Musa'],
            'LB' => ['Beirut', 'Tripoli', 'Sidon', 'Tyre', 'Nabatieh', 'Jounieh', 'Zahle', 'Baalbek', 'Byblos', 'Aley'],
            'IQ' => ['Baghdad', 'Basra', 'Mosul', 'Erbil', 'Najaf', 'Karbala', 'Sulaymaniyah', 'Kirkuk', 'Nasiriyah', 'Amarah', 'Ramadi', 'Fallujah', 'Kut', 'Hilla', 'Duhok', 'Samarra'],
            'SY' => ['Damascus', 'Aleppo', 'Homs', 'Latakia', 'Hama', 'Deir ez-Zor', 'Raqqa', 'Daraa', 'Al-Hasakah', 'Qamishli', 'Tartus', 'Idlib'],
            'YE' => ['Sanaa', 'Aden', 'Taiz', 'Hodeidah', 'Ibb', 'Dhamar', 'Mukalla', 'Zinjibar', 'Saada', 'Marib'],
            'PS' => ['Gaza', 'Ramallah', 'Hebron', 'Nablus', 'Bethlehem', 'Jenin', 'Tulkarm', 'Qalqilya', 'Jericho', 'Khan Yunis', 'Rafah'],
            'MA' => ['Casablanca', 'Rabat', 'Fes', 'Marrakech', 'Tangier', 'Agadir', 'Meknes', 'Oujda', 'Kenitra', 'Tetouan', 'Safi', 'Temara', 'Mohammedia', 'Khouribga', 'El Jadida', 'Beni Mellal', 'Nador', 'Taza', 'Settat'],
            'DZ' => ['Algiers', 'Oran', 'Constantine', 'Annaba', 'Blida', 'Batna', 'Djelfa', 'Sétif', 'Sidi Bel Abbès', 'Biskra', 'Tébessa', 'El Oued', 'Skikda', 'Tiaret', 'Béjaïa', 'Tlemcen', 'Ouargla', 'Béchar', 'Mostaganem', 'Bordj Bou Arréridj'],
            'TN' => ['Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Kasserine', 'Ben Arous', 'Medenine', 'Nabeul', 'Tataouine', 'Béja', 'Jendouba', 'Mahdia', 'Sidi Bouzid', 'Siliana', 'Kef', 'Tozeur', 'Kebili', 'Zaghouan', 'Manouba'],
            'LY' => ['Tripoli', 'Benghazi', 'Misrata', 'Zawiya', 'Bayda', 'Gharyan', 'Tobruk', 'Ajdabiya', 'Sirte', 'Khoms', 'Zliten', 'Sabha', 'Derna'],
            'SD' => ['Khartoum', 'Omdurman', 'Khartoum North', 'Port Sudan', 'Kassala', 'Gedaref', 'Nyala', 'El Obeid', 'El Fasher', 'Wad Madani', 'Atbara'],
            'TR' => ['Istanbul', 'Ankara', 'Izmir', 'Bursa', 'Adana', 'Gaziantep', 'Konya', 'Antalya', 'Kayseri', 'Mersin', 'Eskişehir', 'Diyarbakır', 'Samsun', 'Denizli', 'Şanlıurfa', 'Adapazarı', 'Malatya', 'Kahramanmaraş', 'Erzurum', 'Van', 'Batman', 'Elâzığ', 'İzmit', 'Manisa', 'Sivas', 'Gebze', 'Balıkesir', 'Tarsus', 'Kütahya', 'Trabzon'],
            'GB' => ['London', 'Birmingham', 'Manchester', 'Leeds', 'Liverpool', 'Newcastle', 'Sheffield', 'Bristol', 'Glasgow', 'Edinburgh', 'Cardiff', 'Belfast', 'Nottingham', 'Leicester', 'Coventry', 'Bradford', 'Southampton', 'Brighton', 'Portsmouth', 'Plymouth'],
            'US' => ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose', 'Austin', 'Jacksonville', 'Fort Worth', 'Columbus', 'San Francisco', 'Charlotte', 'Indianapolis', 'Seattle', 'Denver', 'Washington', 'Boston', 'Detroit', 'Nashville', 'Memphis', 'Portland', 'Oklahoma City', 'Las Vegas', 'Louisville', 'Baltimore', 'Milwaukee'],
            'CA' => ['Toronto', 'Montreal', 'Vancouver', 'Calgary', 'Edmonton', 'Ottawa', 'Winnipeg', 'Quebec City', 'Hamilton', 'Kitchener', 'London', 'Victoria', 'Halifax', 'Oshawa', 'Windsor', 'Saskatoon', 'Regina', 'St. Catharines', 'Kelowna', 'Barrie'],
            'FR' => ['Paris', 'Marseille', 'Lyon', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille', 'Rennes', 'Reims', 'Le Havre', 'Saint-Étienne', 'Toulon', 'Grenoble', 'Dijon', 'Angers', 'Nîmes', 'Villeurbanne'],
            'DE' => ['Berlin', 'Hamburg', 'Munich', 'Cologne', 'Frankfurt', 'Stuttgart', 'Düsseldorf', 'Dortmund', 'Essen', 'Leipzig', 'Bremen', 'Dresden', 'Hanover', 'Nuremberg', 'Duisburg', 'Bochum', 'Wuppertal', 'Bielefeld', 'Bonn', 'Münster'],
            'IT' => ['Rome', 'Milan', 'Naples', 'Turin', 'Palermo', 'Genoa', 'Bologna', 'Florence', 'Bari', 'Catania', 'Venice', 'Verona', 'Messina', 'Padua', 'Trieste', 'Brescia', 'Taranto', 'Prato', 'Parma', 'Modena'],
            'ES' => ['Madrid', 'Barcelona', 'Valencia', 'Seville', 'Zaragoza', 'Málaga', 'Murcia', 'Palma', 'Las Palmas', 'Bilbao', 'Alicante', 'Córdoba', 'Valladolid', 'Vigo', 'Gijón', 'Hospitalet de Llobregat', 'A Coruña', 'Granada', 'Vitoria-Gasteiz', 'Elche'],
        ];

        $citiesAdded = 0;
        $citiesSkipped = 0;

        foreach ($countriesWithCities as $countryCode => $cities) {
            $country = Country::where('code', $countryCode)->first();

            if (!$country) {
                $this->command->warn("⚠️  The country {$countryCode} is not found, skipping the cities");
                continue;
            }

            foreach ($cities as $cityName) {
                $city = City::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'name' => $cityName
                    ],
                    [
                        'is_available' => true,
                    ]
                );

                if ($city->wasRecentlyCreated) {
                    $citiesAdded++;
                } else {
                    $citiesSkipped++;
                }
            }

            $this->command->info("✅ {$country->name}: Processed " . count($cities) . " city");
        }

        $this->command->info("✅ Added {$citiesAdded} new cities");
        $this->command->info("ℹ️  Skipped {$citiesSkipped} existing cities");
    }
}

