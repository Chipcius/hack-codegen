<?hh // strict
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\Str;

final class HackfmtFormatter implements ICodegenFormatter {
  public function format(
    string $code,
    string $file_name,
  ): string {
    $output = array();
    $exit_code = null;

    $tempnam = \tempnam(
      \sys_get_temp_dir(),
      'hack-codegen-hackfmt',
    );
    try {
      \file_put_contents($tempnam, $code);
      \exec(
        'hackfmt '.\escapeshellarg($tempnam),
        &$output,
        &$exit_code,
      );
    } finally {
      \unlink($tempnam);
    }

    invariant(
      $exit_code === 0,
      'Failed to invoke hackfmt',
    );
    return \implode("\n", $output)."\n";
  }
}
