<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * News seed.
 */
class NewsSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Antonelli sorprende a McLaren: ¿cuál es el mejor neumático para el Sprint?',
                'slug' => 'antonelli-sorprende-a-mclaren-cual-es-el-mejor-neumatico-para-el-sprint',
                'description' => 'Inesperada primera posición en parrilla del piloto italiano, sólido en la clasificación al SprintMcLaren hizo su trabajo, pero el de Mercedes impuso su ley.El medio debería ser el compuesto elegido para la carrera',
                'body' => 'La sesión de clasificación para la carrera al Sprint empezó mostrando una gran actuación de Antonelli. Era una prueba donde tenían que rendir muy bien los McLaren, pero fue el joven piloto de Mercedes quien se colocó al frente, con diferencias muy escasas en cada parte de la vuelta. Piastri acabó a cuatro centésimas, por la parte final de la vuelta, Norris a ocho por el comienzo del giro, pero ambos por detrás. Verstappen sólo pudo aguantar en el primer tramo, a partir de la segunda zona de la vuelta perdió distancia y se descolgó, como también le sucedió a Russell, que había salido unos minutos antes. Ferrari acabó como cuarto coche, a tres décimas de la cabeza, con problemas en cada parte de la vuelta. No muy lejos de Red Bull, pero sí de los McLaren y de Antonelli.

La zona media estuvo liderada en esta ocasión por Albon, aunque aquí el punto clave fue ver quién pudo acceder a la ronda final y cambiar a neumático blando. Albon, Hadjar y Alonso lo hicieron, y fue el Williams el mejor de ellos, por un gran sector central. Hadjar y Alonso estuvieron unas décimas por detrás, colocadas en la zona central de la vuelta. Hulkenberg, Sainz y Ocon ya no pudieron colocar este neumático al no superar el segundo corte. Hulkenberg perdió todo al comienzo de la vuelta, Sainz no pudo cerrar el giro al tener un problema en la frenada para la curva 11 y Ocon perdió bastante más en el segundo y tercer tramo. Alpine fue, con ello, el peor equipo de la parrilla, con muchos problemas en las tres partes de la vuelta. También destacó la eliminación de Tsunoda en la primera ronda con su Red Bull, en una peligrosa y dañina línea de resultados.

A continuación vamos a estudiar lo que nos dejó cada zona. ',
                'user_id' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Los pilotos de McLaren no se dan por vencidos: "Podemos luchar en el Sprint"',
                'slug' => 'los-pilotos-de-mclaren-no-se-dan-por-vencidos-podemos-luchar-en-el-sprint',
                'description' => 'Ninguno de los dos ha logrado superar al Mercedes de Andrea Kimi AntonelliEl objetivo para mañana es claro: pelear por la victoria',
                'body' => 'McLaren ha mostrado músculo en la sesión de clasificación para el Sprint del GP de Miami con Oscar Piastri y Lando Norris en los puestos de cabeza, aunque sin lograr la Pole. El joven Andrea Kimi Antonelli ha sorprendido al arrebatar el lugar de privilegio a los de Woking por apenas 45 milésimas. Pese a ello, ambos se mantienen confiados de cara a la carrera, con el objetivo de remontar y sumar puntos importantes.

Si bien McLaren no estará al frente en la parrilla de salida del Sprint de mañana, Oscar Piastri, segundo más rápido, ha mostrado determinación y asegura que su objetivo es pelear por el triunfo para ampliar su ventaja en el campeonato.

"Estoy bastante contento, aunque no fue la mejor vuelta de mi vida", ha reconocido el australiano. "Tuve un bloqueo en la última curva, que probablemente fue lo que me llevó a perder la pole, pero el segundo puesto sigue siendo un buen resultado; aún podemos luchar a partir de ahí en el Sprint, así que, en general, estoy bastante contento", explica.',
                'user_id' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Piastri mete miedo en los Libres 1 de Miami; Sainz, cuarto',
                'slug' => 'piastri-mete-miedo-en-los-libres-1-de-miami-sainz-cuarto',
                'description' => 'Norris se encontró unas herramientas en el cockpit apenas unos segundos después de salir a pista por primera vez
Buenas sensaciones para el equipo Williams, con Sainz y Albon cuarto y quinto respectivamente; Alonso, décimo',
                'body' => 'Oscar Piastri se muestra fuerte en Miami. El piloto australiano se colocó primero en su intento con neumático blando que le hizo estar a tres décimas del segundo, Charles Leclerc. Lando Norris no pudo completar su vuelta rápida por un accidente de Oliver Bearman que causó bandera roja a cuatro minutos del final de la sesión. Buenas sensaciones para Carlos Sainz, que finaliza los únicos entrenamientos del fin de semana en cuarta posición.

Los equipos llegan a Miami con nueva apariencia. Ferrari estrena decoración, así como Racing Bulls también lo hace. Y Lando Norris, Charles Leclerc, Lewis Hamilton, Alex Albon e Isack Hadjar llevan un casco diferente. Estar en Florida les inspiró a ello, por lo que parece.

El formato al Sprint de este fin de semana en Miami otorga todavía más importancia a esta primera sesión de libres. No había tiempo que perder, ni para los equipos ni para los pilotos, que necesitaban exprimir al máximo los 60 minutos de pruebas. El resto del fin de semana ya se disputará en clave Sprint o enfocado a la carrera del domingo. Es decir, ‘fuego real’.',
                'user_id' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        /*$this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->table('news')->truncate();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');*/

        $table = $this->table('news');
        $table->insert($data)->save();
    }
}
