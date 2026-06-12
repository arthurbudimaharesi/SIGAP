<?php

$dir = new RecursiveDirectoryIterator('resources/views');
$iterator = new RecursiveIteratorIterator($dir);

$map = [
    '✅' => '<span class="material-symbols-outlined align-middle">check_circle</span>',
    '🟢' => '<span class="material-symbols-outlined align-middle text-green-500">task_alt</span>',
    '❌' => '<span class="material-symbols-outlined align-middle">cancel</span>',
    '🔴' => '<span class="material-symbols-outlined align-middle text-red-500">error</span>',
    '🚨' => '<span class="material-symbols-outlined align-middle text-red-500">error</span>',
    '⏳' => '<span class="material-symbols-outlined align-middle">hourglass_top</span>',
    '🕐' => '<span class="material-symbols-outlined align-middle">schedule</span>',
    '🔧' => '<span class="material-symbols-outlined align-middle">build</span>',
    '⭐' => '<span class="material-symbols-outlined align-middle text-yellow-500">star</span>',
    '🎉' => '<span class="material-symbols-outlined align-middle">celebration</span>',
    '📝' => '<span class="material-symbols-outlined align-middle">edit_document</span>',
    '✏️' => '<span class="material-symbols-outlined align-middle">edit</span>',
    '📄' => '<span class="material-symbols-outlined align-middle">description</span>',
    '📋' => '<span class="material-symbols-outlined align-middle">list_alt</span>',
    '📊' => '<span class="material-symbols-outlined align-middle">bar_chart</span>',
    '📥' => '<span class="material-symbols-outlined align-middle">download</span>',
    '🚩' => '<span class="material-symbols-outlined align-middle text-red-500">flag</span>',
    '🏠' => '<span class="material-symbols-outlined align-middle">home</span>',
    '🔍' => '<span class="material-symbols-outlined align-middle">search</span>',
    '👥' => '<span class="material-symbols-outlined align-middle">group</span>',
    '👷' => '<span class="material-symbols-outlined align-middle">engineering</span>',
    '👤' => '<span class="material-symbols-outlined align-middle">person</span>',
    '📜' => '<span class="material-symbols-outlined align-middle">history</span>',
    '🔔' => '<span class="material-symbols-outlined align-middle">notifications</span>',
    '👋' => '<span class="material-symbols-outlined align-middle text-yellow-500">waving_hand</span>',
    '💧' => '<span class="material-symbols-outlined align-middle text-[#022448]">water_drop</span>',
    '➕' => '<span class="material-symbols-outlined align-middle">add</span>',
    '✨' => '<span class="material-symbols-outlined align-middle">magic_button</span>',
    '💾' => '<span class="material-symbols-outlined align-middle">save</span>',
    '🏘️' => '<span class="material-symbols-outlined align-middle">house</span>',
    '🏷️' => '<span class="material-symbols-outlined align-middle">label</span>',
    '🗺️' => '<span class="material-symbols-outlined align-middle">map</span>',
    '⏱️' => '<span class="material-symbols-outlined align-middle">timer</span>',
];

$count = 0;
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        
        $changed = false;
        foreach ($map as $emoji => $icon) {
            if (strpos($content, $emoji) !== false) {
                $content = str_replace($emoji, $icon, $content);
                $changed = true;
            }
        }
        
        if ($changed) {
            file_put_contents($path, $content);
            echo "Updated: $path\n";
            $count++;
        }
    }
}

echo "Total files updated: $count\n";
