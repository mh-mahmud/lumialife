<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlet_locations', function (Blueprint $table) {
            $table->id();
            $table->string('location_name', 150);
            $table->text('address');
            $table->string('hotline', 50);
            $table->text('map_url');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('outlet_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image')->nullable();
            $table->timestamps();
        });

        $now = now();
        $outlets = [
            ['Mirpur 1 (Dhaka)', 'Rupayan Latifa Shamsuddin Square (opposite of Sony Square), 1st Floor, Mirpur Section 1, Dhaka', '01332502911', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3069.714512295742!2d90.35571362755202!3d23.799875666793998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c14977a020ef%3A0xea738f68516b9a5a!2sFabrilife!5e0!3m2!1sen!2sbd!4v1730535919852!5m2!1sen!2sbd'],
            ['Uttara (Dhaka)', 'Level 3, Plot - 67 (Meena Bazar Building), Gausul Azam Avenue, Sector 14, Uttara, Dhaka 1230', '01332502910', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3648.578295120551!2d90.38374011215896!3d23.86910397850021!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c50037c40075%3A0x2eb20c0e30b623f1!2sFabrilife%20Uttara%20Outlet!5e0!3m2!1sen!2sbd!4v1730535973054!5m2!1sen!2sbd'],
            ['Mohammadpur (Dhaka)', 'Urban Life, Level 1, House: 18-A/4, Block: F (Near Hatil Showroom), Ring Road, Adabor, Dhaka', '01827080121', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.4009381493406!2d90.35577611215679!3d23.76873297856885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c10037d60baf%3A0x4b1f9dec5874626b!2sFabrilife%20Mohammadpur%20Outlet!5e0!3m2!1sen!2sbd!4v1730535990238!5m2!1sen!2sbd'],
            ['Khilgoan (Dhaka)', '926/C (Besides Blue Moon Restaurant & Apan Coffee House), Taltola More, Khilgoan, Dhaka 1219', '01332502906', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.8819908621226!2d90.42021331215646!3d23.751587278580626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b9002d5e80c5%3A0x1d5f6af4577ce33!2sFabrilife%20Khilgaon!5e0!3m2!1sen!2sbd!4v1730536277096!5m2!1sen!2sbd'],
            ['GEC (Chittagong)', 'Madina Tower, Level 2, Opposite of Hotel Peninsula, Beside Yunusco City Centre, GEC, Chattogram', '01620220606', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3689.8957791500075!2d91.81869441212753!3d22.357563779560678!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30acd9c7b19c37dd%3A0x1695a20f97af77e7!2sFabrilife%20Chattogram%20GEC%20Outlet!5e0!3m2!1sen!2sbd!4v1730536297158!5m2!1sen!2sbd'],
            ['Khulna', 'Ground Floor, Beside Miniso, 27 KDA Approach Rd, Khulna', '01332836616', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3677.440540647982!2d89.54748767600398!3d22.823185223738154!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ff91e495a45c7d%3A0x5889a1215b930d14!2sFabrilife%20Khulna%20Outlet!5e0!3m2!1sen!2sbd!4v1738218320810!5m2!1sen!2sbd'],
            ['Kushtia', 'Ground Floor, Chand Mohammad Rd, Opposite of Aarong, Kushtia-7000', '01332836617', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d911.8589190797671!2d89.12742431543988!3d23.90962179706477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fe97001cdd5195%3A0x86ef39739967215e!2sFabrilife%20Kushtia%20Outlet!5e0!3m2!1sen!2sbd!4v1738218017507!5m2!1sen!2sbd'],
            ['Jamuna Future Park', '1st Floor, Through Center Court, Beside ILLIYEEN & Yellow, Jamuna Future Park, Progoti Shoroni, Dhaka', '01332836615', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.1422244897517!2d90.42165901215776!3d23.813541078538208!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c62fb95f16c1%3A0xb333248370356dee!2sJamuna%20Future%20Park!5e0!3m2!1sen!2sbd!4v1730536332390!5m2!1sen!2sbd'],
            ['Sylhet', '1st Floor, House- 34, Block- A, Kumarpara Road, Kumarpara, Sylhet', '01324264998', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3619.030800474299!2d91.8783084!3d24.896931000000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x375055b16f823d19%3A0xcabfc289b2c14750!2sFabrilife%20Sylhet%20Outlet!5e0!3m2!1sen!2sbd!4v1752580127252!5m2!1sen!2sbd'],
            ['Banani', 'Beside Sheraton, between Bata & Bay, Kamal Ataturk Avenue, Banani, Dhaka', '01332502929', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d228.1685739170851!2d90.40492882234824!3d23.793795291473806!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c79c5ba6fa3b%3A0x5f1002e9eca6bc64!2sDelta%20Dahlia!5e0!3m2!1sen!2sbd!4v1752580307998!5m2!1sen!2sbd'],
            ['Rajshahi', 'South Side of New Market, 341 Station Rd, Rajshahi 6100', '+8801324264999', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1864992.163147838!2d88.1581217054132!3d24.081695308463363!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fbef02162ba0bb%3A0xefd17480f9e9f102!2sFabrilife%20Rajshahi%20Outlet!5e0!3m2!1sen!2sbd!4v1755880435171!5m2!1sen!2sbd'],
            ['Dhanmondi', 'House-56, Road No. 3A, Jigatola, Dhanmondi, Dhaka', '+8801324264997', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.205057455127!2d90.37230701163426!3d23.74006597858851!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b9c7bd0134cb%3A0x392f3b40bc596c46!2sFabrilife%20Dhanmondi%20Outlet!5e0!3m2!1sen!2sbd!4v1755880788752!5m2!1sen!2sbd'],
            ['Wari', '28 Rankin St, Dhaka', '+0881335183197', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d913.1938139725384!2d90.41516857611931!3d23.719718523048954!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8541365f65d%3A0x13c9a75479f74e8a!2s28%20Rankin%20St%2C%20Dhaka%201203!5e0!3m2!1sen!2sbd!4v1769426988915!5m2!1sen!2sbd'],
        ];

        DB::table('outlet_locations')->insert(array_map(
            fn (array $outlet, int $index) => [
                'location_name' => $outlet[0],
                'address' => $outlet[1],
                'hotline' => $outlet[2],
                'map_url' => $outlet[3],
                'sort_order' => $index + 1,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $outlets,
            array_keys($outlets)
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('outlet_page_settings');
        Schema::dropIfExists('outlet_locations');
    }
};
