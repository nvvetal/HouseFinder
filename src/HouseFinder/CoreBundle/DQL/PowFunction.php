<?php
namespace HouseFinder\CoreBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class PowFunction extends FunctionNode
{
    public $numberExpression = null;
    public $powerExpression = 1;
    public function parse(Parser $parser)
    {
        //Check for correct
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->numberExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->powerExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'POW(' .
        $this->numberExpression->dispatch($sqlWalker) . ', ' .
        $this->powerExpression->dispatch($sqlWalker) . ')';
    }
}