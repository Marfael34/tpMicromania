<?php

/**
 * ============================================
 * ENTITÉ MEDIA
 * ============================================
 * 
 * Entité pour gérer les médias (images, fichiers, etc.)
 * En relation ManyToMany avec Todo
 */

declare(strict_types=1);

namespace App\Entity;

use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Index;

#[Entity(table: 'media')]
class Media
{
    #[Id]
    #[Column(type: 'integer', autoIncrement: true)]
    public ?int $id = null;
    
    #[Column(type: 'string', length: 255)]
    public string $filename;
    
    #[Column(type: 'string', length: 255)]
    public string $original_filename;
    
    #[Column(type: 'string', length: 100, nullable: true)]
    public ?string $mime_type = null;
    
    #[Column(type: 'integer', nullable: true)]
    public ?int $size = null;
    
    #[Column(type: 'string', length: 500)]
    public string $path;
    
    #[Column(type: 'string', length: 50, nullable: true)]
    public ?string $type = null; // 'image', 'document', 'video', etc.
    
    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTime $created_at = null;
    
    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTime $updated_at = null;
    
    /**
     * Retourne l'URL complète du média
     */
    public function getUrl(): string
    {
        return $this->path;
    }
    
    /**
     * Vérifie si le média est une image
     */
    public function isImage(): bool
    {
        return $this->type === 'image' || str_starts_with($this->mime_type ?? '', 'image/');
    }
    
    /**
     * Retourne la taille formatée
     */
    public function getFormattedSize(): string
    {
        if ($this->size === null) {
            return '0 B';
        }
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }
}
