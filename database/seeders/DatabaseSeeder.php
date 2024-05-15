<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\ReadFileImportSubjectService;
//use DB;

class DatabaseSeeder extends Seeder
{

    private $_subject_import;

    public function __construct(ReadFileImportSubjectService $_subject_import)
	{
        $this->_subject_import = $_subject_import;
    }
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $_subject = $this->_subject_import->_subject;
        $_tab = $this->_subject_import->_tab;

        // \App\Models\User::factory(10)->create();
        // \App\Models\ManageNotificationTemplate::factory(10)->create();

        //\App\Models\Subject::factory(10)->create();
        // \App\Models\UserPoint::factory(10)->create();
        // \App\Models\UserBlock::factory(10)->create();
        // \App\Models\UserRating::factory(10)->create();
        // insert data subject

            $this->call([
                SchoolMasterSeeder::class
                // SubjectSeeder::class,
                // TagSeeder::class,
                // SchoolSeeder::class,
            ]);

        // $this->call(SubjectSeeder::class,false,compact('_subject'));
        // $this->call(TagSeeder::class,false,compact('_tab'));

        // \App\Models\Request::factory(10)->create();
        // \App\Models\UserSubject::factory(10)->create();
    }
}
