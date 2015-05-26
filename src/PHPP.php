<?php
# Copyright (c) 2014 Marcelo Camargo <marcelocamargo@linuxmail.org>
#
# Permission is hereby granted, free of charge, to any person
# obtaining a copy of this software and associated documentation files
# (the "Software"), to deal in the Software without restriction,
# including without limitation the rights to use, copy, modify, merge,
# publish, distribute, sublicense, and/or sell copies of the Software,
# and to permit persons to whom the Software is furnished to do so,
# subject to the following conditions:
#
# The above copyright notice and this permission notice shall be
# included in all copies or substantial of portions the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

namespace PHPP
{
  abstract class LexerBase
  {
    const EOF   =      -1;
    const T_EOF = "T_EOF";

    protected $input;
    protected $position = 0;
    protected $char;
    public static $dictionary = [];

    public function __construct($input)
    {
      $this->input = $input;
      $this->char = $input[$this->position];
    }

    public function ahead($plus = 1)
    {
      return $this->input[$this->position + $plus];
    }

    public function maybe($value)
    {
      for ($i = 0, $len = strlen($value); $i < $len; $i++) {
        if ($this->ahead($i) !== $value[$i]) {
          return false;
        }
      }
      return true;
    }

    public function consume($amount = 1)
    {
      $this->position += $amount;
      if ($this->position >= strlen($this->input)) {
        $this->char = self::EOF;
      } else {
        $this->char = $this->input[$this->position];
      }
    }

    public abstract function nextToken();
  }

  class Lexer extends LexerBase
  {
    public function __construct($input)
    {
      parent::__construct($input);
    }

    public function emit()
    {
      echo $this->char;
      $this->consume();
    }

    public function nextToken()
    {
      while ($this->char !== self::EOF) {
        switch ($this->char) {
          case "#":
            $this->maybeHint();
            continue;
          default:
            if (ctype_alpha($this->char)) {
              $this->searchKeywordTable();
            }
            $this->emit();
        }
      }
    }

    private function maybeHint()
    {
      $this->consume();
      $keyword = "keyword";
      if ($this->maybe($keyword)) {
        $buffer = $this->consume(strlen($keyword));
        $this->oneOrMoreWS();
        $this->keyword();
      }
    }

    private function oneOrMoreWS() {
      do {
        $this->consume();
      } while ($this->char === " ");
    }

    private function keyword()
    {
      $keyword = $this->nextIdentifier();
      $this->oneOrMoreWS();
      $translate = $this->nextIdentifier();
      LexerBase::$dictionary[$keyword] = $translate;
    }

    private function nextIdentifier() {
      $identifier = "";
      if (ctype_alpha($this->char) || $this->char === "_") {
        $identifier .= $this->char;
        $this->consume();
        while (ctype_alnum($this->char) || $this->char === "_") {
          $identifier .= $this->char;
          $this->consume();
        }
      }

      return $identifier;
    }

    private function searchKeywordTable()
    {
      $identifier = "";
      if (ctype_alpha($this->char) || $this->char === "_") {
        $identifier .= $this->char;
        $this->consume();
        while (ctype_alnum($this->char) || $this->char === "_") {
          $identifier .= $this->char;
          $this->consume();
        }
      }

      $this->position -= strlen($identifier);
      $this->char = $this->input[$this->position];

      if (isset(LexerBase::$dictionary[$identifier])) {
        $this->emitKeyword($identifier, LexerBase::$dictionary[$identifier]);
      }
    }

    private function emitKeyword($from, $to) {
      $this->consume(strlen($from));
      echo LexerBase::$dictionary[$from];
    }
  }

  $source = file_get_contents($argv[1]);
  $token = new Lexer($source);
  $token->nextToken();
}