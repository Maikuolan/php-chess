<?php

namespace Chess\Tests\Unit\Play;

use Chess\Play\RavPlay;
use Chess\Tests\AbstractUnitTestCase;

class RavPlayTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5()
    {
        $movetext = '1.e4 e5';
        $board = (new RavPlay($movetext))->play()->getBoard();

        $this->assertSame($movetext, $board->getMovetext());
    }

    /**
     * @test
     */
    public function breakdown_e4_e5__h5()
    {
        $movetext = '1. e4 e5 2. Nf3 Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5 (11. Nb1 h6 12. h4 (12. Nh4 g5 13. Nf5) 12... a5 13. g4 Nxg4) 11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5 (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $breakdown = (new RavPlay($movetext))->getBreakdown();

        $expected = [
            '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5',
            '11.Nb1 h6 12.h4',
            '12.Nh4 g5 13.Nf5',
            '12...a5 13.g4 Nxg4',
            '11...Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5',
            '16.hxg5 Bxg5 17.Nxg5 hxg5 18.Rh5',
        ];

        $this->assertSame($expected, (new RavPlay($movetext))->getBreakdown());
    }

    /**
     * @test
     */
    public function breakdown_starting_with_ellipsis_Nc6__h5()
    {
        $movetext = '2...Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5 (11. Nb1 h6 12. h4 (12. Nh4 g5 13. Nf5) 12... a5 13. g4 Nxg4) 11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5 (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $breakdown = (new RavPlay($movetext))->getBreakdown();

        $expected = [
            '2...Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5',
            '11.Nb1 h6 12.h4',
            '12.Nh4 g5 13.Nf5',
            '12...a5 13.g4 Nxg4',
            '11...Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5',
            '16.hxg5 Bxg5 17.Nxg5 hxg5 18.Rh5',
        ];

        $this->assertSame($expected, (new RavPlay($movetext))->getBreakdown());
    }

    /**
     * @test
     */
    public function breakdown_Ra7_Kg8__Rc8()
    {
        $movetext = '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8 (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#) 6.Kd6 (6.Kc6 Kd8) 6...Kb8 (6...Kd8 7.Ra8#) 7.Rc7 Ka8 8.Kc6 Kb8 9.Kb6 Ka8 10.Rc8#';

        $expected = [
            '1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8',
            '5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#',
            '6.Kd6',
            '6.Kc6 Kd8',
            '6...Kb8',
            '6...Kd8 7.Ra8#',
            '7.Rc7 Ka8 8.Kc6 Kb8 9.Kb6 Ka8 10.Rc8#',
        ];

        $this->assertSame($expected, (new RavPlay($movetext))->getBreakdown());
    }

    /**
     * @test
     */
    public function breakdown_Ke2_Kd5__Ra1()
    {
        $movetext = '1.Ke2 Kd5 2.Ke3 Kc4 (2...Ke5 3.Rh5+) (2...Kc4 3.Rh5) 3.Rh5 (3...Kb4 4.Kd3) 3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $expected = [
            '1.Ke2 Kd5 2.Ke3 Kc4',
            '2...Ke5 3.Rh5+',
            '2...Kc4 3.Rh5',
            '3.Rh5',
            '3...Kb4 4.Kd3',
            '3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#',
        ];

        $this->assertSame($expected, (new RavPlay($movetext))->getBreakdown());
    }

    /**
     * @test
     */
    public function breakdown_starting_with_ellipsis_Kc4__Ra1()
    {
        $movetext = '2...Kc4 (2...Ke5 3.Rh5+) (2...Kc4 3.Rh5) 3.Rh5 (3...Kb4 4.Kd3) 3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#';

        $expected = [
            '2...Kc4',
            '2...Ke5 3.Rh5+',
            '2...Kc4 3.Rh5',
            '3.Rh5',
            '3...Kb4 4.Kd3',
            '3...Kc3 4.Rh4 Kc2 5.Rc4+ Kb3 6.Kd3 Kb2 7.Rb4+ Ka3 8.Kc3 Ka2 9.Ra4+ Kb1 10.Ra5 Kc1 11.Ra1#',
        ];

        $this->assertSame($expected, (new RavPlay($movetext))->getBreakdown());
    }

    /**
     * @test
     */
    public function play_e4_e5__h5()
    {
        $movetext = '1. e4 e5 2. Nf3 Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5 (11. Nb1 h6 12. h4 (12. Nh4 g5 13. Nf5) 12... a5 13. g4 Nxg4) 11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5 (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $board = (new RavPlay($movetext))->play()->getBoard();

        $expected = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5';

        $this->assertSame($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function fen_e4_e5__h5()
    {
        $movetext = '1. e4 e5 2. Nf3 Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5 (11. Nb1 h6 12. h4 (12. Nh4 g5 13. Nf5) 12... a5 13. g4 Nxg4) 11... Nxd5 12. Bxd5 Rb8 13. h4 h6 14. Rdg1 a5 15. g4 g5 16. h5 (16. hxg5 Bxg5 17. Nxg5 hxg5 18. Rh5)';

        $expected = [
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6',
            'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -',
            'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/2N2N2/PPPP1PPP/R1BQK2R b KQkq -',
            'r1bqk2r/ppppbppp/2n2n2/1B2p3/4P3/2N2N2/PPPP1PPP/R1BQK2R w KQkq -',
            'r1bqk2r/ppppbppp/2n2n2/1B2p3/4P3/2NP1N2/PPP2PPP/R1BQK2R b KQkq -',
            'r1bqk2r/ppp1bppp/2np1n2/1B2p3/4P3/2NP1N2/PPP2PPP/R1BQK2R w KQkq -',
            'r1bqk2r/ppp1bppp/2np1n2/1B2p3/4P3/2NPBN2/PPP2PPP/R2QK2R b KQkq -',
            'r2qk2r/pppbbppp/2np1n2/1B2p3/4P3/2NPBN2/PPP2PPP/R2QK2R w KQkq -',
            'r2qk2r/pppbbppp/2np1n2/1B2p3/4P3/2NPBN2/PPPQ1PPP/R3K2R b KQkq -',
            'r2qk2r/1ppbbppp/p1np1n2/1B2p3/4P3/2NPBN2/PPPQ1PPP/R3K2R w KQkq -',
            'r2qk2r/1ppbbppp/p1np1n2/4p3/B3P3/2NPBN2/PPPQ1PPP/R3K2R b KQkq -',
            'r2qk2r/2pbbppp/p1np1n2/1p2p3/B3P3/2NPBN2/PPPQ1PPP/R3K2R w KQkq b6',
            'r2qk2r/2pbbppp/p1np1n2/1p2p3/4P3/1BNPBN2/PPPQ1PPP/R3K2R b KQkq -',
            'r2q1rk1/2pbbppp/p1np1n2/1p2p3/4P3/1BNPBN2/PPPQ1PPP/R3K2R w KQ -',
            'r2q1rk1/2pbbppp/p1np1n2/1p2p3/4P3/1BNPBN2/PPPQ1PPP/2KR3R b - -',
            'r2q1rk1/2pbbppp/p1np1n2/4p3/1p2P3/1BNPBN2/PPPQ1PPP/2KR3R w - -',
            'r2q1rk1/2pbbppp/p1np1n2/3Np3/1p2P3/1B1PBN2/PPPQ1PPP/2KR3R b - -',
            'r2q1rk1/2pbbppp/p1np1n2/4p3/1p2P3/1B1PBN2/PPPQ1PPP/1NKR3R b - -',
            'r2q1rk1/2pbbpp1/p1np1n1p/4p3/1p2P3/1B1PBN2/PPPQ1PPP/1NKR3R w - -',
            'r2q1rk1/2pbbpp1/p1np1n1p/4p3/1p2P2P/1B1PBN2/PPPQ1PP1/1NKR3R b - h3',
            'r2q1rk1/2pbbpp1/p1np1n1p/4p3/1p2P2N/1B1PB3/PPPQ1PPP/1NKR3R b - -',
            'r2q1rk1/2pbbp2/p1np1n1p/4p1p1/1p2P2N/1B1PB3/PPPQ1PPP/1NKR3R w - g6',
            'r2q1rk1/2pbbp2/p1np1n1p/4pNp1/1p2P3/1B1PB3/PPPQ1PPP/1NKR3R b - -',
            'r2q1rk1/2pbbpp1/2np1n1p/p3p3/1p2P2P/1B1PBN2/PPPQ1PP1/1NKR3R w - -',
            'r2q1rk1/2pbbpp1/2np1n1p/p3p3/1p2P1PP/1B1PBN2/PPPQ1P2/1NKR3R b - g3',
            'r2q1rk1/2pbbpp1/2np3p/p3p3/1p2P1nP/1B1PBN2/PPPQ1P2/1NKR3R w - -',
            'r2q1rk1/2pbbppp/p1np4/3np3/1p2P3/1B1PBN2/PPPQ1PPP/2KR3R w - -',
            'r2q1rk1/2pbbppp/p1np4/3Bp3/1p2P3/3PBN2/PPPQ1PPP/2KR3R b - -',
            '1r1q1rk1/2pbbppp/p1np4/3Bp3/1p2P3/3PBN2/PPPQ1PPP/2KR3R w - -',
            '1r1q1rk1/2pbbppp/p1np4/3Bp3/1p2P2P/3PBN2/PPPQ1PP1/2KR3R b - h3',
            '1r1q1rk1/2pbbpp1/p1np3p/3Bp3/1p2P2P/3PBN2/PPPQ1PP1/2KR3R w - -',
            '1r1q1rk1/2pbbpp1/p1np3p/3Bp3/1p2P2P/3PBN2/PPPQ1PP1/2K3RR b - -',
            '1r1q1rk1/2pbbpp1/2np3p/p2Bp3/1p2P2P/3PBN2/PPPQ1PP1/2K3RR w - -',
            '1r1q1rk1/2pbbpp1/2np3p/p2Bp3/1p2P1PP/3PBN2/PPPQ1P2/2K3RR b - g3',
            '1r1q1rk1/2pbbp2/2np3p/p2Bp1p1/1p2P1PP/3PBN2/PPPQ1P2/2K3RR w - g6',
            '1r1q1rk1/2pbbp2/2np3p/p2Bp1pP/1p2P1P1/3PBN2/PPPQ1P2/2K3RR b - -',
            '1r1q1rk1/2pbbp2/2np3p/p2Bp1P1/1p2P1P1/3PBN2/PPPQ1P2/2K3RR b - -',
            '1r1q1rk1/2pb1p2/2np3p/p2Bp1b1/1p2P1P1/3PBN2/PPPQ1P2/2K3RR w - -',
            '1r1q1rk1/2pb1p2/2np3p/p2Bp1N1/1p2P1P1/3PB3/PPPQ1P2/2K3RR b - -',
            '1r1q1rk1/2pb1p2/2np4/p2Bp1p1/1p2P1P1/3PB3/PPPQ1P2/2K3RR w - -',
            '1r1q1rk1/2pb1p2/2np4/p2Bp1pR/1p2P1P1/3PB3/PPPQ1P2/2K3R1 b - -',
        ];

        $this->assertSame($expected, (new RavPlay($movetext))->getFen());
    }
}