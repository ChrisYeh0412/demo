<?php
use App\Models\Constellation;
use Illuminate\Database\Seeder;

class ConstellationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('constellations')->truncate();
        $starSign = ['水瓶座', '雙魚座', '牡羊座', '金牛座', '雙子座', '巨蟹座', '獅子座', '處女座', '天秤座', '天蠍座', '射手座', '摩羯座'];

        foreach ($starSign as $value) {
            factory(Constellation::class)->create([
                'name' => $value,
            ]);
        }
    }
}
