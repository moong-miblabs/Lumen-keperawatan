<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

use Illuminate\Support\Facades\DB;

class SetupModel extends Model implements AuthenticatableContract, AuthorizableContract{
    use Authenticatable, Authorizable, HasFactory;

    public static function dbsync(){
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS users(
                id CHAR(36) PRIMARY KEY,
                nama_user VARCHAR(60),
                username_user VARCHAR(60),
                password_user VARCHAR(60),
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL,
                deleted_at TIMESTAMP WITH TIME ZONE DEFAULT NULL
            );

            CREATE TABLE IF NOT EXISTS respondens(
                id CHAR(36) PRIMARY KEY,
                nama_responden VARCHAR(60),
                username_responden VARCHAR(60),
                password_responden VARCHAR(60),
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL,
                deleted_at TIMESTAMP WITH TIME ZONE DEFAULT NULL
            );

            CREATE TABLE IF NOT EXISTS demografi(
                id CHAR(36) PRIMARY KEY,
                responden_id CHAR(36), FOREIGN KEY (responden_id) REFERENCES respondens(id) ON DELETE SET NULL ON UPDATE CASCADE,
                kelompok CHAR(2),
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL,
                deleted_at TIMESTAMP WITH TIME ZONE DEFAULT NULL
            );
            COMMENT ON COLUMN demografi.kelompok is 'II : Intervensi, KK : Kontrol, XX : kelompok X, dua huruf digunakan jika kelompok intervensi ataupun kontrol lebih dari satu';

            CREATE TABLE IF NOT EXISTS prepos(
                id CHAR(36) PRIMARY KEY,
                responden_id CHAR(36), FOREIGN KEY (responden_id) REFERENCES respondens(id) ON DELETE SET NULL ON UPDATE CASCADE,
                jenis CHAR(3),
                p1 SMALLINT,
                hasil_score FLOAT,
                hasil_class VARCHAR(60),
                hasil_desc TEXT,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL,
                deleted_at TIMESTAMP WITH TIME ZONE DEFAULT NULL
            );
            COMMENT ON COLUMN prepos.jenis is 'PRE : Pre Test, POS : POS Test';

            /*
            CREATE TABLE IF NOT EXISTS debug(
                id SERIAL PRIMARY KEY,
                input1 JSON,
                input2 JSONB
            );
            */
        ");
    }

    public static function seed(){
        DB::unprepared("
            INSERT INTO users(id,nama_user,username_user,password_user,created_at,updated_at,deleted_at) VALUES ('00000000-0000-0000-0000-0000000admin','PENELITI','admin','\$2a\$10\$O2hW0NX1QqFTzxZaGeD2Zepb5FQvGu5DY220yvZO93K2zv3.rWB4y',NOW(),NOW(),NULL);

            INSERT INTO respondens(id,nama_responden,username_responden,password_responden,created_at,updated_at,deleted_at) VALUES ('00000000-0000-0000-0000-000responden','IRFAN','a','\$2a\$10\$Rj0xHBNme3IvwNcYOqcDE.rKWzpSuKzj8idv9KE8jTiqk9gdJHFFu',NOW(),NOW(),NULL);
            INSERT INTO demografi(id,responden_id,kelompok,created_at,updated_at,deleted_at) VALUES ('00000000-0000-0000-0000-00demografi','00000000-0000-0000-0000-000responden','II',NOW(),NOW(),NULL);
        ");
    }

    public static function drop(){
        DB::unprepared("
            /* DROP TABLE IF EXISTS debug; */

            DROP TABLE IF EXISTS prepos;
            DROP TABLE IF EXISTS demografi;
            DROP TABLE IF EXISTS respondens;
            DROP TABLE IF EXISTS users;
        ");
    }
}
