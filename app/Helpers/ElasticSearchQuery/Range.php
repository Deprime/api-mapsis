<?php

declare(strict_types=1);

namespace App\Helpers\ElasticSearchQuery;

use JeroenG\Explorer\Domain\Syntax\SyntaxInterface;

class Range implements SyntaxInterface
{
  private string $field;
  private ?float $gte;
  private ?float $lte;

  /**
   * Range constructor.
   * @param string $field
   * @param float|null $gte (Optional) Greater than or equal to.
   * @param float|null $lte (Optional) Less than or equal to.
   */
  public function __construct(string $field, ?float $gte, ?float $lte)
  {
    $this->field = $field;
    $this->gte = $gte;
    $this->lte = $lte;
  }

  public function build(): array
  {
    $condition = [];
    $condition['boost'] = 1.0;

    if(!is_null($this->gte)){
      $condition['gte'] = $this->gte;
    }

    if(!is_null($this->lte)){
      $condition['lte'] = $this->lte;
    }

    return [
      'range' => [
        $this->field => $condition,
      ]
    ];
  }
}
