<?php
// =============================================================================
// 📁 File: backend/classes/models/TTSModel.php
// 📦 Project: Speakify
// 📜 Description: Resolves TTS audio file paths from SHA1 hashes
// =============================================================================

class TTSModel
{
    /**
     * Given a SHA1 hash, returns the full path to the audio file
     *
     * @param string $hash
     * @return string|null
     */
    public static function resolvePathFromHash(string $hash): ?string
    {
        // 🔒 Basic validation
        if (!preg_match('/^[a-f0-9]{40}$/', $hash)) {
            return null;
        }

        // 🧭 Folder structure logic
        $dir1 = substr($hash, 0, 2);
        $dir2 = substr($hash, 2, 2);
        $dir3 = substr($hash, 4, 2);
        $filename = substr($hash, 6) . '.mp3';

        $fullPath = BASEPATH . "/backend/storage/audio/$dir1/$dir2/$dir3/$filename";

        return file_exists($fullPath) ? $fullPath : null;
    }
}
