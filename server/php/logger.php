<?php
class Logger {
    // Абсолютный путь к лог-файлу
    private static $logFile;
    private static $maxFileSize = 1048576; // 1MB максимальный размер файла
    private static $maxBackups = 5; // Максимальное количество backup-файлов

    /**
     * Инициализация логгера
     */
    private static function init() {
        if (self::$logFile === null) {
            // Устанавливаем абсолютный путь к лог-файлу
            self::$logFile = __DIR__ . '/../../server/php/app.log'; // Путь относительно расположения класса Logger
            
            // Создаем директорию для логов, если ее нет
            $logDir = dirname(self::$logFile);
            if (!file_exists($logDir)) {
                mkdir($logDir, 0777, true);
            }
        }
    }

    /**
     * Записывает сообщение в лог-файл
     * @param string $message Сообщение для записи
     * @param string $level Уровень важности (INFO, WARNING, ERROR и т.д.)
     */
    public static function log($message, $level = 'INFO') {
        self::init();
        
        // Проверяем и ротируем лог-файл при необходимости
        self::rotateLog();
        
        // Форматируем запись
        $timestamp = date('[Y-m-d H:i:s]');
        $logEntry = "$timestamp [$level] $message" . PHP_EOL;
        
        // Записываем в файл
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Ротация лог-файла при превышении максимального размера
     */
    private static function rotateLog() {
        if (file_exists(self::$logFile)) {
            // Проверяем размер файла
            if (filesize(self::$logFile) >= self::$maxFileSize) {
                $logDir = dirname(self::$logFile);
                
                // Удаляем самые старые backup-файлы
                for ($i = self::$maxBackups; $i > 0; $i--) {
                    $oldFile = self::$logFile . '.' . $i;
                    if (file_exists($oldFile)) {
                        if ($i === self::$maxBackups) {
                            unlink($oldFile);
                        } else {
                            rename($oldFile, self::$logFile . '.' . ($i + 1));
                        }
                    }
                }
                
                // Переименовываем текущий лог-файл
                rename(self::$logFile, self::$logFile . '.1');
            }
        }
    }
}




// require_once 'logger.php';

// // Простое сообщение
// Logger::log("Приложение запущено");

// // С разным уровнем важности
// Logger::log("Пользователь вошел в систему", "INFO");
// Logger::log("Неудачная попытка входа", "WARNING");
// Logger::log("Ошибка подключения к БД", "ERROR");

// // С переменными
// $userId = 123;
// Logger::log("Пользователь с ID $userId совершил действие", "DEBUG");