<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVcardView extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $query = "CREATE VIEW vcard_view AS
                    SELECT ps.id,
                    ps.name,
                    vc.fullname,
                    vc.user_id,
                    vc.unity_id,
                    vc.sector_id,
                    vp.number,
                    cs.id AS case_id,
                    cs.name AS case_name,
                    un.name AS unity_name,
                    st.name AS sector_name
                   FROM vcards vc
                     LEFT JOIN vcard_phones vp ON vp.vcard_id = vc.id
                     LEFT JOIN persons ps ON ps.id = vc.person_id
                     LEFT JOIN case_persons cp ON cp.person_id = ps.id
                     LEFT JOIN cases cs ON cs.id = cp.case_id
                     LEFT JOIN unitys un ON un.id = cs.unity_id
                     LEFT JOIN sectors st ON st.id = cs.sector_id;";
        DB::statement($query);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('vcard_view');
    }
}
