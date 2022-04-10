<?php

namespace App\Classes\KanbanBoard;

use Michelf\Markdown;
use App\Classes\Utilities;

class Application
{

	public function __construct(object $github, array|string $repositories, $paused_labels = [])
	{
		$this->github = $github;
		$this->repositories = $repositories;
		$this->paused_labels = $paused_labels;
	}

	public function board()
	{
		$receivedMilestones = $this->getMilestonesInformation();
		return $this->setParametersForBoard($receivedMilestones);;
	}

	private function getMilestonesInformation(): array
	{
		$result = [];
		foreach ($this->repositories as $repository) {
			foreach ($this->github->milestones($repository) as $data) {
				$result[$data['title']] = $data;
				$result[$data['title']]['repository'] = $repository;
			}
		}
		ksort($result);
		return $result;
	}

	private function setParametersForBoard(array $receivedMilestones): array
	{
		$result = [];
		foreach ($receivedMilestones as $name => $data) {
			$issues = $this->issues($data['repository'], $data['number']);
			$percent = self::_percent($data['closed_issues'], $data['open_issues']);
			if ($percent) {
				$result[] = [
					'milestone' => $name,
					'url' => isset($data['html_url']) ? $data['html_url'] : null,
					'progress' => $percent,
					'queued' => isset($issues['queued']) ? $issues['queued'] : null,
					'active' => isset($issues['active']) ? $issues['active'] : null,
					'completed' => isset($issues['completed']) ? $issues['completed'] : null,
				];
			}
		}
		return $result;
	}

	private function issues(string $repository, int|string $milestone_id): ?array
	{
		$receivedIssues = $this->github->issues($repository, $milestone_id);
		$issues = $this->setIssuesArray($receivedIssues);
		return $this->setResultOfIssue($issues);
	}

	private function setResultOfIssue(array $issues): ?array
	{
		if (isset($issues['active'])) {
			usort($issues['active'], function ($a, $b) {
				return $this->differencePausedsOrTitles($a, $b);
			});
			return $issues;
		} else if (isset($issues))
			return $issues;
		else
			return null;
	}

	private function differencePausedsOrTitles(array $a, array $b)
	{
		return count($a['paused']) - count($b['paused']) === 0 ? strcmp($a['title'], $b['title']) : count($a['paused']) - count($b['paused']);
	}

	private function setIssuesArray(array $receivedIssues): array
	{
		$issues = [];
		foreach ($receivedIssues as $receivedIssue) {
			if (isset($receivedIssue['pull_request']))
				continue;
			$issues[$receivedIssue['state'] === 'closed' ? 'completed' : (($receivedIssue['assignee']) ? 'active' : 'queued')][] = $this->setIndexOfIssuesArray($receivedIssue);
		}
		return $issues;
	}

	private function setIndexOfIssuesArray(array $receivedIssue): array
	{
		return [
			'id' => $receivedIssue['id'], 'number' => $receivedIssue['number'],
			'title'            	=> $receivedIssue['title'],
			'body'             	=> Markdown::defaultTransform($receivedIssue['body']),
			'url' => $receivedIssue['html_url'],
			'assignee'         	=> $this->setAssigneeForIssues($receivedIssue),
			'paused'			=> self::labels_match($receivedIssue, $this->paused_labels),
			'progress'			=> self::_percent(
				substr_count(strtolower($receivedIssue['body']), '[x]'),
				substr_count(strtolower($receivedIssue['body']), '[ ]')
			),
			'closed'			=> $receivedIssue['closed_at']
		];
	}

	private function setAssigneeForIssues(array $receivedIssue): ?string
	{
		return (is_array($receivedIssue) && array_key_exists('assignee', $receivedIssue) && !empty($receivedIssue['assignee'])) ? $receivedIssue['assignee']['avatar_url'] . '?s=16' : NULL;
	}

	private static function _state($issue): string
	{
		if ($issue['state'] === 'closed')
			return 'completed';
		else if (Utilities::hasValue($issue, 'assignee') && count($issue['assignee']) > 0)
			return 'active';
		else
			return 'queued';
	}

	private static function labels_match(array $issue, $needles): array
	{
		if (Utilities::hasValue($issue, 'labels')) {

			foreach ($issue['labels'] as $label) {
				if (in_array($label['name'], $needles))
					return [$label['name']];
			}
		}
		return [];
	}

	private static function _percent(int|string $complete, int|string $remaining): array
	{
		$total = $complete + $remaining;
		if ($total > 0) {
			return [
				'total' => $total,
				'complete' => $complete,
				'remaining' => $remaining,
				'percent' => ($complete or $remaining) ? round($complete / $total * 100) : 0
			];
		}
		return [];
	}
}
