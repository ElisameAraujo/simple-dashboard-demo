# 🎞️ MediaHelper

A classe **MediaHelper** fornece funções utilitárias para manipulação e exibição de mídias (imagens, vídeos, PDFs, etc.) armazenadas nos discos configurados no Laravel (`config/filesystems.php`).  
Ela simplifica operações comuns como verificar existência, gerar URLs públicas, obter caminhos internos, baixar arquivos e identificar MIME types.

---

## 📂 Funções disponíveis

### `mediaExists(?string $disk = 'public', ?string $path = null): bool`

Verifica se uma mídia existe em um disco.

-   `$disk`: Disco configurado no `filesystems.php`. Opcional, padrão `public`.
-   `$path`: Caminho relativo da mídia dentro do disco.

Retorna `true` se o arquivo existir, `false` caso contrário.

```php
MediaHelper::mediaExists('meu-disco', 'uploads/avatar.jpg');
// true ou false
```

---

### `showMedia(string $path, ?string $disk = 'public', ?string $placeholder = null): ?string`

Retorna a **URL pública** da mídia ou um _placeholder_ se o arquivo não existir.

-   `$path`: Caminho relativo da mídia dentro do disco.
-   `$disk`: Disco configurado no `filesystems.php`. Opcional, padrão `public`.
-   `$placeholder`: Caminho para imagem/arquivo de fallback em `public/`. Opcional.

```blade
<img src="{{ MediaHelper::showMedia('uploads/avatar.jpg') }}" />

<img src="{{ MediaHelper::showMedia('uploads/avatar.jpg', 'meu-disco', 'images/default-avatar.png') }}" />
```

---

### `mediaFullPath(string $path, ?string $disk = 'public'): ?string`

Retorna o caminho completo relativo ao projeto, **sem o APP_URL**.

-   `$path`: Caminho relativo da mídia.
-   `$disk`: Disco configurado no `filesystems.php`. Opcional, padrão `public`.

```php
MediaHelper::mediaFullPath('uploads/file.pdf', 'meu-disco');
// "/storage/meu-disco/uploads/file.pdf"
```

---

### `downloadMedia(string $path, ?string $customName = null, ?string $disk = 'public')`

Retorna uma **response de download** da mídia.

-   `$path`: Caminho relativo da mídia.
-   `$customName`: Nome personalizado para o arquivo baixado. Opcional.
-   `$disk`: Disco configurado no `filesystems.php`. Opcional, padrão `public`.

Se `$customName` não for informado, usa automaticamente o **basename** do arquivo.

```php
// download com nome real do arquivo
return MediaHelper::downloadMedia('reports/relatorio-final.pdf');

// download com nome customizado
return MediaHelper::downloadMedia('reports/relatorio-final.pdf', 'Relatorio.pdf');

// download em outro disco
return MediaHelper::downloadMedia('reports/relatorio-final.pdf', null, 'meu-disco');
```

---

### `mediaMimeType(string $path, ?string $disk = 'public'): string`

Retorna o **MIME type** da mídia (ex.: `image/jpeg`, `video/mp4`).  
Se o arquivo não existir ou não conseguir identificar, retorna `"mimetype unknown"`.

-   `$path`: Caminho relativo da mídia.
-   `$disk`: Disco configurado no `filesystems.php`. Opcional, padrão `public`.

```php
MediaHelper::mediaMimeType('uploads/avatar.jpg');
// "image/jpeg"

MediaHelper::mediaMimeType('videos/demo.mp4', 'meu-disco');
// "video/mp4"

MediaHelper::mediaMimeType('arquivo-inexistente.txt', 'meu-disco');
// "mimetype unknown"
```

---

## ✅ Observações importantes

-   O parâmetro `$disk` sempre se refere ao disco configurado em `config/filesystems.php`.
-   O parâmetro `$path` deve ser o caminho relativo dentro do disco (ex.: `uploads/file.jpg`).
-   O parâmetro `$placeholder` é opcional e só é usado em `showMedia`.
-   `downloadMedia` aborta com `404` se o arquivo não existir.
-   `mediaMimeType` nunca retorna `null`, sempre uma string válida.
