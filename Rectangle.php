<?php

class Rectangle
{
    public int $x1;
    public int $y1;
    public int $x2;
    public int $y2;

    /**
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     */
    public function __construct(int $x1, int $y1, int $x2, int $y2)
    {
        $this->x1 = $x1;
        $this->y1 = $y1;
        $this->x2 = $x2;
        $this->y2 = $y2;
    }

    public function isOverlap(Rectangle $rectangle): bool {
        $rect_a = $this;
        $rect_b = $rectangle;
        return($rect_a->x1 < $rect_b->x2 &&
            $rect_a->x2 > $rect_b->x1 &&
            $rect_a->y1 < $rect_b->y2 &&
            $rect_a->y2 > $rect_b->y1);
    }

    /**
     * @param Rectangle[] $rectangles
     * @return bool
     */
    public function isOverlapMulti(array $rectangles): bool {
        foreach ($rectangles as $rectangle) {
            if($this->isOverlap($rectangle)) {
                return true;
            }
        }
        return false;
    }
}