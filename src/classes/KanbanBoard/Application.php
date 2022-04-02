<?php

namespace App\Classes\KanbanBoard;

use Michelf\Markdown;
use App\Classes\Utilities;

class Application
{

	public function __construct($github, $repositories, $paused_labels = array())
	{
		$this->github = $github;
		$this->repositories = $repositories;
		$this->paused_labels = $paused_labels;
	}

	public function board()
	{
		$getedMilestones = $this->getMilestonesInformation();
		$milestones = $this->setParametersForBoard($getedMilestones);
		return $milestones;
	}

	private function getMilestonesInformation()
	{
		$result = array();
		foreach ($this->repositories as $repository) {
			foreach ($this->github->milestones($repository) as $data) {
				$result[$data['title']] = $data;
				$result[$data['title']]['repository'] = $repository;
			}
		}
		ksort($result);
		return $result;
	}

	private function setParametersForBoard($getedMilestones)
	{
		$result = [];
		foreach ($getedMilestones as $name => $data) {
			$issues = $this->issues($data['repository'], $data['number']);
			$percent = self::_percent($data['closed_issues'], $data['open_issues']);
			if ($percent) {
				$result[] = array(
					'milestone' => $name,
					'url' => isset($data['html_url']) ? $data['html_url'] : null,
					'progress' => $percent,
					'queued' => isset($issues['queued']) ? $issues['queued'] : null,
					'active' => isset($issues['active']) ? $issues['active'] : null,
					'completed' => isset($issues['completed']) ? $issues['completed'] : null,
				);
			}
		}
		return $result;
	}

	private function issues($repository, $milestone_id)
	{
		$getedIssues = $this->github->issues($repository, $milestone_id);
		$issues = $this->setIssuesArray($getedIssues);
		return $this->setResultOfIssue($issues);
	}

	private function setResultOfIssue($issues)
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

	private function differencePausedsOrTitles($a, $b)
	{
		return count($a['paused']) - count($b['paused']) === 0 ? strcmp($a['title'], $b['title']) : count($a['paused']) - count($b['paused']);
	}

	private function setIssuesArray($getedIssues)
	{
		foreach ($getedIssues as $getedIssue) {
			if (isset($getedIssue['pull_request']))
				continue;
			$issues[$getedIssue['state'] === 'closed' ? 'completed' : (($getedIssue['assignee']) ? 'active' : 'queued')][] = array(
				'id' => $getedIssue['id'], 'number' => $getedIssue['number'],
				'title'            	=> $getedIssue['title'],
				'body'             	=> Markdown::defaultTransform($getedIssue['body']),
				'url' => $getedIssue['html_url'],
				'assignee'         	=> (is_array($getedIssue) && array_key_exists('assignee', $getedIssue) && !empty($getedIssue['assignee'])) ? $getedIssue['assignee']['avatar_url'] . '?s=16' : NULL,
				'paused'			=> self::labels_match($getedIssue, $this->paused_labels),
				'progress'			=> self::_percent(
					substr_count(strtolower($getedIssue['body']), '[x]'),
					substr_count(strtolower($getedIssue['body']), '[ ]')
				),
				'closed'			=> $getedIssue['closed_at']
			);
		}
		return $issues;
	}

	private static function _state($issue)
	{
		if ($issue['state'] === 'closed')
			return 'completed';
		else if (Utilities::hasValue($issue, 'assignee') && count($issue['assignee']) > 0)
			return 'active';
		else
			return 'queued';
	}

	private static function labels_match($issue, $needles)
	{
		if (Utilities::hasValue($issue, 'labels')) {
			foreach ($issue['labels'] as $label) {
				if (in_array($label['name'], $needles)) {
					return array($label['name']);
				}
			}
		}
		return array();
	}

	private static function _percent($complete, $remaining)
	{
		$total = $complete + $remaining;
		if ($total > 0) {
			$percent = ($complete or $remaining) ? round($complete / $total * 100) : 0;
			return array(
				'total' => $total,
				'complete' => $complete,
				'remaining' => $remaining,
				'percent' => $percent
			);
		}
		return array();
	}
}
