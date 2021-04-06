<?php

namespace Drupal\copyright_acknowledgement_link\Plugin\Field\FieldFormatter;

use Drupal\file\Plugin\Field\FieldFormatter\FileFormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'file_download_acknowledgement' formatter.
 *
 * @FieldFormatter(
 *   id = "file_download_acknowledgement",
 *   label = @Translation("Download Copyright Acknowledgement"),
 *   description = @Translation("Display the file as a form acknowledging a copyright notice."),
 *   field_types = {
 *     "file",
 *     "image"
 *   }
 * )
 */
class FileDownloadAcknowledgement extends FileFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $file) {
      $view_value = $file->label();

      $paren_parts = [
        $file->getMimeType(),
        $this->humanFileSize($file->getSize()),
      ];
      $paren_parts = array_filter($paren_parts);
      if ($paren_parts) {
        $view_value = $view_value . ' (' . implode("; ", $paren_parts) . ')';
      }
      $url = Url::fromUri(file_create_url($file->getFileUri()));
      if ($url) {
        $elements[$delta] = [
          '#theme' => 'copyright_acknowledgement_link',
          '#label' => $view_value,
          '#url' => $url,
          '#checkbox' => TRUE,
          // @todo Remove the 'url.site' cache context by using a relative file
          // URL (file_url_transform_relative()). This is currently impossible
          // because #type => link requires a Url object, and Url objects do not
          // support relative URLs: they require fully qualified URLs. Fix in
          // https://www.drupal.org/node/2646744.
          '#cache' => [
            'contexts' => [
              'url.site',
            ],
          ],
        ];
      }
      else {
        $elements[$delta] = is_array($view_value) ? $view_value : ['#markup' => $view_value];
      }
    }

    return $elements;
  }

  /**
   * Return a human-friendly filesize.
   *
   * See: https://stackoverflow.com/a/15188082
   */
  public function humanFileSize($size, $unit = "") {
    if ((!$unit && $size >= 1 << 30) || $unit == "GB") {
      return number_format($size / (1 << 30), 2) . " GB";
    }
    if ((!$unit && $size >= 1 << 20) || $unit == "MB") {
      return number_format($size / (1 << 20), 2) . " MB";
    }
    if ((!$unit && $size >= 1 << 10) || $unit == "KB") {
      return number_format($size / (1 << 10), 2) . " KB";
    }
    return number_format($size) . " bytes";
  }

}
