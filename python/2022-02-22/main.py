# Leetcode - Two Sum
# Follow-up: Can you come up with an algorithm that is less than O(n2) time complexity?

from timeit import default_timer
from typing import List
from collections import defaultdict

class Solution0:
    def twoSum(self, nums: List[int], target: int) -> List[int]:
        x = [0, 0]
        newNums = nums[:]
        while len(newNums) > 0:
            for b in range(1, len(newNums)):
                if newNums[0] + newNums[b] == target:
                    x[0] = [i for i, value in enumerate(
                        nums) if value == newNums[0]][0]
                    x[1] = [i for i, value in enumerate(
                        nums) if value == newNums[b] and i != x[0]][0]
                    return x
            newNums.pop(0)


class Solution2:
    def twoSum(self, nums: List[int], target: int) -> List[int]:
        for i in range(len(nums)-1):
            x = target - nums[i]
            j = [j for j, v in enumerate(nums) if v == x and j != i]
            if j != []:
                return [i, j[0]]


class Solution1:
    def twoSum(self, nums: List[int], target: int) -> List[int]:
        lookup = defaultdict(list)
        for i, num in enumerate(nums):
            needed = target - num
            if needed in lookup:
                return [lookup[needed][0], i]
            lookup[num].append(i)
        return lookup

start = default_timer()
print("1. [5, 8]: ", str(Solution1.twoSum(1, [1, 6, 7, 8, 9, 4, 4, 3, 2], 6)))
print(default_timer() - start, "\n")

start = default_timer()
print("2. [0, 1]: ", str(Solution1.twoSum(1, [-3, 9, 3, 90], 6)))
print(default_timer() - start, "\n")

start = default_timer()
print("3. [2, 3]: ", str(Solution1.twoSum(1, [1, 3, 4, 2], 6)))
print(default_timer() - start, "\n")

start = default_timer()
print("4. [0, 1]: ", str(Solution1.twoSum(1, [3, 3], 6)))
print(default_timer() - start, "\n")

start = default_timer()
print("4. [0, 3]: ", str(Solution1.twoSum(1, [0, 4, 3, 0], 0)))
print(default_timer() - start, "\n")

start = default_timer()
print("5. [0, 3]: ", str(Solution1.twoSum(1, [6, 2, 5, 6], 12)))
print(default_timer() - start, "\n")

start = default_timer()
print("6.", str(Solution1.twoSum(1, [x for x in range(2000)], 3997)))
print(default_timer() - start, "\n")

start = default_timer()
print("7.", str(Solution1.twoSum(1, [x for x in range(20000)], 39997)))
print(default_timer() - start, "\n")
